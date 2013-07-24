<?php

namespace Jiwen\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GeneralController extends Controller
{
	public function floatCartAction()
	{
        return $this->render('JiwenGeneralBundle:General:floatCart.html.twig');
	}
}
