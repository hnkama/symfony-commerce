<?php

namespace Jiwen\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GeneralController extends Controller
{
	public function floatCartAction()
	{
		$provider = $this->get('sylius.cart_provider'); // Implements the CartProviderInterface.
    	$currentCart = $provider->getCart();

		$session = $this->getRequest()->getSession();
		$productHistory = $session->get('productHistory', array());

		// 读取最新浏览产品

        return $this->render('JiwenGeneralBundle:General:floatCart.html.twig', array(
			'cart' => $currentCart,
			'productHistory' => $productHistory,
		));
	}

	public function giftRecommendAction()
	{
		$productRepository = $this->container->get('sylius.repository.product');
		$propertyRepository = $this->container->get('sylius.repository.property');
		$property = $propertyRepository->findOneBy(array('name' => '基文礼物推荐'));
        return $this->render('JiwenGeneralBundle:General:giftRecommend.html.twig', array(
			'gifts' => $productRepository->getByPropery(null, $property, 1, 6),
			'blank_form' => $this->createFormBuilder()
            ->getForm()->createView(),
		));
	}
}
