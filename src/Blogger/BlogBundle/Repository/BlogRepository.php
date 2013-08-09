<?php
namespace Blogger\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Blogger\BlogBundle\Entity\Blog;

use Pagerfanta\Adapter\DoctrineORMAdapter;
use Doctrine\DBAL\Query\QueryBuilder;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter;

class BlogRepository extends EntityRepository {
	/*
	 * 文章列表分页类
	 * */
	public function showlist($page=1)
	{
		$queryBuilder =  $this->getEntityManager()->createQuery(
    			"SELECT p FROM BloggerBlogBundle:Blog p WHERE p.is_open != 0 ORDER BY p.important DESC"
    		);
		$adapter = new DoctrineORMAdapter($queryBuilder);
		$list = new Pagerfanta($adapter);
		$list->setMaxPerPage(6);
		$list->setCurrentPage($page);
		return $list;
	}
}