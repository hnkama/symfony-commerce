<?php
namespace Blogger\AdminBundle\Controller;

use Blogger\BlogBundle\Entity\Classify;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ClassifyController extends Controller
{
	/*
	 * 分类列表页
	 * */
	
	public function indexAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$classify =$em->getRepository('BloggerBlogBundle:Classify');
		$column=$classify->findAll();
		return $this->render('BloggerAdminBundle:Classify:index.html.twig',array(
				'classify'=>$column
				));
	}
	/*
	 * 分类添加页
	 * */
	public function addAction()
	{
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$contact=$request->get('classify');
			$classify=new Classify();
			$classify->setClassify($contact);
			$classify->setFId('0');
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($classify);
			$em->flush();
			return $this->redirect($this->generateUrl('blogger_classify_homepage'));
		}
		return $this->render('BloggerAdminBundle:Classify:add.html.twig');
	}
	
	/*
	 * 文章分类删除
	 * */
	/*
	public function deleteAction($id){
		$em = $this->getDoctrine()->getEntityManager();
		$product = $em->getRepository('BloggerBlogBundle:Classify')->find($id);
		$em->remove($product);
		$em->flush();
		return $this->redirect($this->generateUrl('blogger_classify_homepage'));
	}
	*/
	/*
	 * 文章分类修改
	* */
	public function modAction($id){
		$em = $this->getDoctrine()->getEntityManager();
		$product = $em->getRepository('BloggerBlogBundle:Classify')->find($id);
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$contact=$request->get('classify');
			$product->setClassify($contact);
			$em->flush();
			return $this->redirect($this->generateUrl('blogger_classify_homepage'));
		}
		return $this->render('BloggerAdminBundle:Classify:mod.html.twig', array(
				'product'=>$product,
		));
	}
}