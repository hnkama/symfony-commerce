<?php

namespace Jiwen\AlipayBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('JiwenAlipayBundle:Default:index.html.twig', array('name' => $name));
    }
}
