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
		if($taxon->getLevel() == 1) {
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

	public function renderBooksVideosIndex($taxon)
	{
        return $this->renderResponse('Frontend/Product:indexBooksVideos.html', array(
            'taxon'    => $taxon,
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
}
