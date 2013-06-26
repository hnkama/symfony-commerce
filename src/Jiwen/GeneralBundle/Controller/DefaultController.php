<?php

namespace Jiwen\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('JiwenGeneralBundle:Default:index.html.twig', array('name' => $name));
    }
}
