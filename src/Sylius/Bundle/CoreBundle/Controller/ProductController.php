<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\Controller;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Product controller.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class ProductController extends ResourceController
{
    /**
     * List products categorized under given taxon.
     *
     * @param Request $request
     * @param $permalink
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function indexByTaxonAction(Request $request, $permalink)
    {
        $config = $this->getConfiguration();

        $taxon = $this->get('sylius.repository.taxon')
            ->findOneByPermalink($permalink);

        if (!isset($taxon)) {
            throw new NotFoundHttpException('Requested taxon does not exist');
        }

		// 如果是一级分类列表，则进行单独处理
		if($taxon->getLevel() == 1 && $taxon->getChildren()->isEmpty() == false) {
			return $this->subIndex($taxon);
		}

        $paginator = $this
            ->getRepository()
            ->createInTaxonPaginator($taxon)
        ;

        $paginator->setCurrentPage($request->query->get('page', 1));
        $paginator->setMaxPerPage($config->getPaginationMaxPerPage());


        return $this->renderResponse('Frontend/Product:indexByTaxon.html', array(
            'taxon'    => $taxon,
            'products' => $paginator,
        ));
    }

	/**
	 * 各个板块的首页，图书页面的首页，生活家居的首页等等
	 * @param Request $request
	 * @param Object $taxon
	 * @return type
	 */
	public function subIndex($taxon)
	{
		if($taxon->getName() == '图书音像') {
			return $this->renderBooksVideosIndex($taxon);
		}

        return $this->renderResponse('Frontend/Product:indexBooksVideos.html', array(
            'taxon'    => $taxon,
        ));
	}

	/**
	 * 图书和视频的首页
	 */
	public function renderBooksVideosIndex($taxon)
	{
		$productRepository = $this->container->get('sylius.repository.product');
		$category = array();
		$top10 = array();
		foreach($taxon->getChildren() as $row) {
			$category[] = $row;
			$top10[] = $productRepository->getTop10($row, 7, array('saleQuantity'=>'DESC'));
			if($row->getName() == '图书') {
				$newestBooks = array();
				foreach($row->getChildren() as $key => $sub_taxon) {
					$newestBooks[$key]['taxon'] = $sub_taxon;
					$newestBooks[$key]['products'] = $productRepository->getByTaxon($sub_taxon, 5, array('createdAt'=>'DESC'));
				}
			} else if($row->getName() == '音像') {
				$newestVideo = array();
				foreach($row->getChildren() as $key => $sub_taxon) {
					$newestVideo[$key]['taxon'] = $sub_taxon;
					$newestVideo[$key]['products'] = $productRepository->getByTaxon($sub_taxon, 5, array('createdAt'=>'DESC'));
				}
			}
		}

		// fetch top 10
		// 读取小编推荐内容
		$propertyRepository = $this->container->get('sylius.repository.property');
		$property = $propertyRepository->findOneBy(array('name' => '小编推荐'));
		$taxonomyRepository = $this->container->get('sylius.repository.taxon');
		$taxonBook = $taxonomyRepository->findOneByName('图书');
		$recommend = array();
		foreach($taxonBook->getChildren() as $key => $staxon) {
			$recommend[$key]['products'] = $productRepository->getByPropery($staxon, $property, 1);
			$recommend[$key]['taxon'] = $staxon;
		}

		// 新书试读,最新的图书

        return $this->renderResponse('Frontend/Product:indexBooksVideos.html', array(
            'taxon'    => $taxon,
			'category' => $category,
			'top10' => $top10,
			'recommendBooks' => $recommend,
			'blank_form' => $this->createFormBuilder()
            ->getForm()->createView(),
			'newestBooks' => $newestBooks,
			'videosFocus' => $newestVideo
			));
	}

    /**
     * Render product filter form.
     *
     * @param Request
     */
    public function filterFormAction(Request $request)
    {
        $form = $this->getFormFactory()->createNamed('criteria', 'sylius_product_filter');

        return $this->renderResponse('filterForm.html', array(
            'form' => $form->createView()
        ));
    }

    private function getFormFactory()
    {
        return $this->get('form.factory');
    }

    /**
     * Get single resource by its identifier.
     */
    public function showAction()
    {
		$entity = $this->findOr404();

		$session = $this->getRequest()->getSession();
		$productHistory = $session->get('productHistory', new \Symfony\Component\HttpFoundation\ParameterBag);
		$productHistory->set($entity->getId(), array(
			'id'=>$entity->getId(),
			'slug'=>$entity->getSlug(),
			'img'=>$entity->getImage()->getPath(),

				));

		$session->set('productHistory', $productHistory);

        $config = $this->getConfiguration();

        $view =  $this
            ->view()
            ->setTemplate($config->getTemplate('show.html'))
            ->setTemplateVar($config->getResourceName())
            ->setData($entity)
        ;

        return $this->handleView($view);
    }
}
