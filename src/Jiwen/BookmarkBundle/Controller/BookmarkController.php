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
    /**
     * Create new resource or just display the form.
     */
    public function createAction(Request $request)
    {

        $resource = $this->createNew();
		$postData = $request->request->get('jiwen_bookmark');
		$product_id = $postData['product'];

        if ($request->isMethod('POST')) {
			$user = $this->get('security.context')->getToken()->getUser();

			$repository = $this->container->get('sylius.repository.product');
    		$product = $repository->find($product_id); 

			$resource->setUser($user);
			$resource->setProduct($product);
			$resource->setCreated(new \DateTime);

            $this->create($resource);
            $this->setFlash('success', 'create');

        }

    }

}
