<?php

namespace Blogger\AdminBundle\Controller;

use Blogger\BlogBundle\Entity\Blog;
use Blogger\AdminBundle\Form\BlogType;
use Blogger\BlogBundle\Entity\Classify;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogController extends Controller
{
	
	/*
	 *文章列表显示
	 * */
    public function indexAction($page=1)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$repository =$em->getRepository('BloggerBlogBundle:Blog');
    	$classify =$em->getRepository('BloggerBlogBundle:Classify');
    	$my_pager=$repository->showlist($page);
    	$pager=$my_pager->getCurrentPageResults();
        return $this->render('BloggerAdminBundle:Blog:index.html.twig',array(
    			'list'	=>$pager,
    			'mypager'=>$my_pager
    	));
    }
    
    /*
     *文章添加
    * */
    public function addAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$blog = new Blog();
    	$form = $this->createForm(new BlogType(), $blog);
    	$request = $this->getRequest();
    	if ($request->getMethod() == 'POST') {
    		$form->bind($request);
    		if ($form->isValid()) {
	    		$em->persist($blog);
	    		$em->flush();
	    		return $this->redirect($this->generateUrl('blogger_admin_homepage'));
    		}
    	}
    	return $this->render('BloggerAdminBundle:Blog:add.html.twig', array(
    			'form' => $form->createView()
    	));
    }
    /*
     * 文章删除
     * */
    public function deleteAction($id){
    	$em = $this->getDoctrine()->getEntityManager();
    	$product = $em->getRepository('BloggerBlogBundle:Blog')->find($id);
    	$em->remove($product);
    	$em->flush();  	
    	return $this->redirect($this->generateUrl('blogger_admin_homepage'));
    }
    
    /*
     * 文章修改
     * */
    public function modAction($id){
    	$em = $this->getDoctrine()->getEntityManager();
    	$blog = $em->getRepository('BloggerBlogBundle:Blog')->find($id);
    	$form = $this->createForm(new BlogType(), $blog);
    	$request = $this->getRequest();
    	if ($request->getMethod() == 'POST') {
    		$form->bind($request);
    		if ($form->isValid()) {
    			$em->flush();
    			return $this->redirect($this->generateUrl('blogger_admin_homepage'));
    		}	
    	}
    	return $this->render('BloggerAdminBundle:Blog:mod.html.twig', array(
    			'form' => $form->createView(),
    			'blog'=>$blog
    	));
    	
    }
}
