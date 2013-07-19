<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\WebBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Jiwen\GeneralBundle\JiwenGeneralBundle;

/**
 * Frontend homepage controller.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class HomepageController extends Controller
{

    /**
     * Store front page.
     *
     * @return Response
     */
    public function mainAction()
    {
        $theme = $this->container->getParameter('twig.theme', 'default');
        return $this->render('SyliusWebBundle:Frontend/' . $theme . '/Homepage:main.html.twig');
    }

    public function recommendAction()
    {
		$propertyRepository = $this->container->get('sylius.repository.property');
		$productRepository = $this->container->get('sylius.repository.product');
		$productPropertyRepository = $this->container->get('sylius.repository.product_property');
		$orderItemRepository = $this->container->get('sylius.repository.order_item');

		$property = $propertyRepository->findOneBy(array('name' => '今日推荐'));

        $productsTodayRecommend = $productPropertyRepository->findBy(
            array('property' => $property->getId(), 'value' => '是'),
            array('id' => 'DESC'),
            6
        );
        $productsHotSale = $productRepository->findBy(
            array(),
            array('saleQuantity' => 'DESC'),
            6
        );

		// create the blank form
		$blank_form = $this->createFormBuilder()
            ->getForm()
        ;
        return $this->render('SyliusWebBundle:Frontend/' . $this->container->getParameter('twig.theme', 'default') . '/Homepage:recommend.html.twig', array(
        	'todayRecommend' => $productsTodayRecommend,
        	'hotSale' => $productsHotSale,
			'blank_form' => $blank_form->createView(),

        ));
    }

	public function booksVideosAction()
	{
		$productRepository = $this->container->get('sylius.repository.product');
		$taxonomyRepository = $this->container->get('sylius.repository.taxon');
		$taxonomyBooks = $taxonomyRepository->findOneByName('图书');
		$keysProducts = array();
		$start = 0;
		foreach($taxonomyBooks->getChildren() as $key => $child) {
			if($key < 5) {
				$keysProducts[$start]['node'] = $child;
				$keysProducts[$start]['products'] = $productRepository->createByTaxonPaginator($child, 4);
				$start++;
			}
		}

		$taxonomyVideos = $taxonomyRepository->findOneByName('音像');
		foreach($taxonomyVideos->getChildren() as  $key => $child) {
			if($key < 5) {
				$keysProducts[$start]['node'] = $child;
				$keysProducts[$start]['products'] = $productRepository->createByTaxonPaginator($child, 4);
				$start++;
			}
		}

		// 新书飙升榜
		$taxonomy = $taxonomyRepository->findOneByName('图书音像');
        $productsHotSale = $productRepository->createInTaxonPaginator($taxonomy, 5);


        return $this->render('SyliusWebBundle:Frontend/' . $this->container->getParameter('twig.theme', 'default') . '/Homepage:booksVideos.html.twig', array(
			'taxonomyBooks' => $taxonomyBooks,
			'taxonomyVideos' => $taxonomyVideos,
			'productsAll' => $keysProducts,
			'blank_form' => $this->createFormBuilder()
            ->getForm()->createView(),
			'hotSale' => $productsHotSale,
        ));

	}

	/**
	 * 
	 * @param string $category 产品分类的名称
	 * @param string $class div的class
	 * @return type
	 */
	public function subcategoryAction($category, $class)
	{
        $em = JiwenGeneralBundle::getContainer()->get('doctrine')->getEntityManager('default');
		$productRepository = $this->container->get('sylius.repository.product');
		$taxonomyRepository = $this->container->get('sylius.repository.taxon');
		$taxonomy = $taxonomyRepository->findOneByName($category);
		$keys = array();
		$start = 0;
		foreach($taxonomy->getChildren() as $key => $node) {
			if($key < 5) {
				$keys[$start]['node'] = $node;
				$keys[$start]['products'] = $productRepository->createByTaxonPaginator($node, 4);
				$keys[$start]['banner'] = $em->getRepository('JiwenBannerBundle:Banner')->findBanner($node);
				$start++;
			}
		}


        return $this->render('SyliusWebBundle:Frontend/' . $this->container->getParameter('twig.theme', 'default') . '/Homepage:subcategory.html.twig', array(
			'taxonomy' => $taxonomy,
			'taxonomy_products' => $keys,
			'blank_form' => $this->createFormBuilder()
            ->getForm()->createView(),
			'class' => $class,
        ));


	}

}
