<?php

namespace Blogger\BlogBundle\Controller;

use Doctrine\ORM\Mapping\OrderBy;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\ORM\EntityRepository;

use Pagerfanta\Adapter\DoctrineORMAdapter;
use Doctrine\DBAL\Query\QueryBuilder;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter;

use Doctrine\ORM\Query\AST\Functions\SubstringFunction;

class DefaultController extends Controller
{
	/*
	 * 文章列表页
	 * */
    public function indexAction($page)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$repository =$em->getRepository('BloggerBlogBundle:Blog');
    	$my_pager=$repository->showlist($page);//分页类
    	$pager=$my_pager->getCurrentPageResults();
        return $this->render('BloggerBlogBundle:Default:index.html.twig',array(
        		'list'	=>$pager,
        		'mypager'=>$my_pager
        		));
    }
    
    /*
     *文章显示页 
     * */
    public function showAction($id)
    {
    	$em = $this->getDoctrine()->getEntityManager();
       	$blog = $em->getRepository('BloggerBlogBundle:Blog')->find($id);
    	$query=$em->createQuery(
    'SELECT p FROM BloggerBlogBundle:Blog p WHERE p.id < :id AND p.is_open != 0 ORDER BY p.id ASC'
)->setParameter('id', $id)->setMaxResults(1);
    	try {
    		$pre = $query->getSingleResult();//前一页信息
    	} catch (\Doctrine\Orm\NoResultException $e) {
    		$pre="error";
    	}
    	$query2=$em->createQuery(
    'SELECT p FROM BloggerBlogBundle:Blog p WHERE p.id >:id AND p.is_open != 0  ORDER BY p.id ASC'
)->setParameter('id', $id)->setMaxResults(1);
    	try {
    		$next= $query2->getSingleResult();//后一页信息
    	} catch (\Doctrine\Orm\NoResultException $e) {
    		$next = 'error';
    	}
    	if (!$blog) {
    		throw $this->createNotFoundException('Unable to find Blog post.');
    	}
    	return $this->render('BloggerBlogBundle:Blog:show.html.twig', array(
    			'blog'      => $blog,
    			'next'		=>$next,
    			'pre'		=>$pre
    	));
    }
}
