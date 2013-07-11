<?php

namespace Jiwen\BookmarkBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
		if (!$this->container->get('security.context')->isGranted('ROLE_USER')) {
			$msg = "请登录之后添加到产品收藏夹";
		} else {
			if ($request->isMethod('POST')) {
				$user = $this->get('security.context')->getToken()->getUser();

				// test if this product already bookmarked in the db
				$repositoryBookmark = $this->getDoctrine()->getManager()->getRepository('JiwenBookmarkBundle:Bookmark');
				$bookmark = $repositoryBookmark->findBy(array(
					'user' => $user->getId(),
					'product' => $product_id,
				));

				$repository = $this->container->get('sylius.repository.product');
				$product = $repository->find($product_id);

				if (count($bookmark) === 0) {

					$resource->setUser($user);
					$resource->setProduct($product);
					$resource->setCreated(new \DateTime);

					$this->create($resource);
					$msg = $product->getName() . "添加成功！";
				} else {
					$msg = $product->getName() . "已经加入到收藏夹！";
				}
			}
		}

		$serializer = $this->get('jms_serializer');
		$response = new Response($msg);

		return $response;
	}

}
