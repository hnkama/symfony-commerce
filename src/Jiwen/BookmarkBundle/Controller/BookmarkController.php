<?php

namespace Jiwen\BookmarkBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Jiwen\BookmarkBundle\Entity\Bookmark;
use Jiwen\BookmarkBundle\Form\BookmarkType;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;

/**
 * Bookmark controller.
 *
 * @Route("/bookmark")
 */
class BookmarkController extends ResourceController
{

}
