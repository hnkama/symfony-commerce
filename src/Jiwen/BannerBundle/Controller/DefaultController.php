<?php

namespace Jiwen\BannerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('JiwenBannerBundle:Default:index.html.twig', array('name' => $name));
    }
}
