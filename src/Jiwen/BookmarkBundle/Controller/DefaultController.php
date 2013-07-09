<?php

namespace Jiwen\BookmarkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('JiwenBookmarkBundle:Default:index.html.twig', array('name' => $name));
    }
}
