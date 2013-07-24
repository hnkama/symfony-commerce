<?php

namespace Jiwen\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GeneralController extends Controller
{
	public function floatCartAction()
	{
		$provider = $this->get('sylius.cart_provider'); // Implements the CartProviderInterface.

    	$currentCart = $provider->getCart();
        return $this->render('JiwenGeneralBundle:General:floatCart.html.twig', array(
			'cart' => $currentCart,
		));
	}
}
