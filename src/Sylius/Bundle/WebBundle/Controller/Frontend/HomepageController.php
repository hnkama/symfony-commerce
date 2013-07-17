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
		$taxonomyRepository = $this->container->get('sylius.repository.taxon');
		$taxonomy = $taxonomyRepository->findOneByName('图书音像');
		$taxonomy_data = array();
		foreach($taxonomy->getChildren() as $key => $node) {
			$taxonomy_data[$key]['node'] = $node;
//			foreach($node->getChildren() as $sub_key => $sub_node) {
				$taxonomy_data[$key]['children'] = $node->getChildren();
//			}
		}


        return $this->render('SyliusWebBundle:Frontend/' . $this->container->getParameter('twig.theme', 'default') . '/Homepage:booksVideos.html.twig', array(
			'taxonomy' => $taxonomy_data,
        ));

	}

}
