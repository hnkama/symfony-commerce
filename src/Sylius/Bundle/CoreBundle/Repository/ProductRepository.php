<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\Repository;

use Sylius\Bundle\AssortmentBundle\Entity\CustomizableProductRepository;
use Sylius\Bundle\TaxonomiesBundle\Model\TaxonInterface;
use Sylius\Bundle\AssortmentBundle\Entity\Property\ProductProperty;
use Sylius\Bundle\CoreBundle\SyliusCoreBundle;
use Doctrine\ORM\Query\Expr\Join;

/**
 * Product repository.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class ProductRepository extends CustomizableProductRepository
{
    /**
     * Create paginator for products categorized
     * under given taxon.
     *
     * @param TaxonInterface
     *
     * @return PagerfantaInterface
     */
    public function createByTaxonPaginator(TaxonInterface $taxon, $maxResults = null, $request = null)
    {
        $queryBuilder = $this->getCollectionQueryBuilder();

        $queryBuilder
			->select('product')
            ->innerJoin('product.taxons', 'taxon')
            ->andWhere('taxon = :taxon')
            ->setParameter('taxon', $taxon)
			->distinct('product.id')
			->groupBy('product.id')
			->distinct()
        ;
		if($request) {
			if($request->get('sort') == 'sales') {
				$queryBuilder
						->orderBy('product.saleQuantity', 'DESC');
			}
			if($request->get('sort') == 'price') {
				$queryBuilder
				->leftJoin('SyliusCoreBundle:Variant', 'v', Join::WITH, 'v.product = product')
						->orderBy('v.price', 'ASC');
			}
			if($request->get('sort') == 'comments') {
				$queryBuilder
					->addSelect('COUNT(c) as countComments')
					->leftJoin('JiwenCommentBundle:Comment', 'c', Join::WITH, 'c.product = product')
					->orderBy('countComments', 'DESC')
				;
			}
			if($request->get('sort') == 'created') {
				$queryBuilder->orderBy('product.createdAt', 'DESC');
			}
			if($request->get('sort') == 'published') {
				$queryBuilder->orderBy('product.createdAt', 'DESC');
			}
		}
		if(null !== $maxResults) {
			$queryBuilder->setMaxResults($maxResults);
		}

		if($maxResults) {
        	return $queryBuilder->getQuery()->getResult();
		} else {
        	return $this->getPaginator($queryBuilder);
		}
    }

    public function getQueryBuilder()
    {
        return $queryBuilder = $this->getCollectionQueryBuilder();
    }

    /**
     * Create paginator for products categorized
     * under given taxon.
     *
     * @param TaxonInterface
     *
     * @return PagerfantaInterface
     */
    public function createInTaxonPaginator(TaxonInterface $taxon, $maxResults = null)
    {
        $queryBuilder = $this->getCollectionQueryBuilder();

        $queryBuilder
            ->innerJoin('product.taxons', 'taxon')
            ->orWhere('taxon = :taxon')
            ->setParameter('taxon', $taxon)
        ;
		foreach($taxon->getChildren() as $key => $taxon) {
			$queryBuilder
				->orWhere('taxon = :taxon'.$key)
				->setParameter('taxon'.$key, $taxon)
			;
			foreach($taxon->getChildren() as $key2 => $staxon) {
				$queryBuilder
					->orWhere('taxon = :taxon'.$key2)
					->setParameter('taxon'.$key2, $staxon)
				;
			}
		}
		if(null !== $maxResults) {
			$queryBuilder->setMaxResults($maxResults);
		}
        $this->applySorting($queryBuilder, array('createdAt'=>'DESC', 'saleQuantity'=>'DESC'));

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Create filter paginator.
     *
     * @param array $criteria
     * @param array $sorting
     *
     * @return PagerfantaInterface
     */
    public function createFilterPaginator($criteria = array(), $sorting = array())
    {
        $queryBuilder = parent::getCollectionQueryBuilder()
            ->select('product, variant')
            ->leftJoin('product.variants', 'variant')
        ;

        if (!empty($criteria['name'])) {
            $queryBuilder
                ->andWhere('product.name LIKE :name')
                ->setParameter('name', '%'.$criteria['name'].'%')
            ;
        }
        if (!empty($criteria['sku'])) {
            $queryBuilder
                ->andWhere('variant.sku = :sku')
                ->setParameter('sku', $criteria['sku'])
            ;
        }

        if (empty($sorting)) {
            if (!is_array($sorting)) {
                $sorting = array();
            }
            $sorting['updatedAt'] = 'desc';
        }

        $this->applySorting($queryBuilder, $sorting);

        return $this->getPaginator($queryBuilder);
    }

    /**
     * Find X recently added products.
     *
     * @param integer $limit
     *
     * @return ProductInterface[]
     */
    public function findLatest($limit = 10)
    {
        return $this->findBy(array(), array('createdAt' => 'desc'), $limit);
    }

    public function getTop10(TaxonInterface $taxon, $number = 10)
    {
		return $this->getByTaxon($taxon, $number, array('saleQuantity'=>'DESC'));
    }

	public function getByTaxon(TaxonInterface $taxon, $number, $order) {
        $queryBuilder = $this->getCollectionQueryBuilder();

        $queryBuilder
            ->innerJoin('product.taxons', 'taxon')
            ->orWhere('taxon = :taxon')
            ->setParameter('taxon', $taxon)
        ;
		foreach($taxon->getChildren() as $key => $taxon) {
			$queryBuilder
				->orWhere('taxon = :taxon'.$key)
				->setParameter('taxon'.$key, $taxon)
			;
		}
		$queryBuilder->setMaxResults($number);
        $this->applySorting($queryBuilder, $order);
		return $queryBuilder->getQuery()->getResult();

	}

    public function getByPropery(TaxonInterface $taxon = null, $property, $value, $number = 5)
    {
        $queryBuilder = $this->getQueryBuilder();
		$em = $queryBuilder->getEntityManager();

		if($taxon) {
			$taxons = array($taxon->getId());
			foreach($taxon->getChildren() as $row) {
				$taxons[] = $row->getId();
			}
		}

		$sql = 'SELECT * FROM sylius_product_property pp LEFT JOIN sylius_product_taxon pt 
			ON pp.product_id = pt.product_id';
		if (is_object($property)) {
			$sql .= ' WHERE pp.property_id = '.$property->getId().'
				AND pp.value = "'.$value.'"';
		}
		if($taxon) {

			if(is_object($property)) {
				$sql .= '
						AND pt.taxon_id IN  ('.implode(',',$taxons).')';
			} else {
				$sql .= '
						WHERE pt.taxon_id IN  ('.implode(',',$taxons).')';
			}
		}
			$sql .= '
			GROUP BY pp.product_id
			ORDER BY pp.product_id DESC
			LIMIT 0, '.$number.'
			';
		$stmt = $em->getConnection()->prepare($sql);
		$stmt->execute();
		$data = array();
		foreach($stmt->fetchAll() as $row) {
			$data[] = $this->find($row['product_id']);
		}
		return $data;
    }

	/**
	 * 查询收藏最多的产品
	 * @param \Sylius\Bundle\TaxonomiesBundle\Model\TaxonInterface $taxon
	 * @param type $number
	 * @param type $loop
	 * @return type
	 */
	public function getMostBookmarkedProducts(TaxonInterface $taxon, $number = 5, $loop = false)
	{
        $queryBuilder = $this->getQueryBuilder();
		$em = $queryBuilder->getEntityManager();
		$taxons = array();
		foreach($taxon->getChildren() as $sub) {
			$taxons[] = $sub->getId();
			if($loop) {
				foreach($sub->getChildren() as $s_sub) {
					$taxons[] = $s_sub->getId();
				}
			}
		}
		$ids = implode(',', $taxons);
		$sql = 'SELECT DISTINCT b.product, t . * , COUNT( b.product ) count
FROM sylius_bookmark b
LEFT JOIN sylius_product_taxon t ON b.product = t.product_id
WHERE t.taxon_id IN ('.$ids.')
GROUP BY b.product
ORDER BY count DESC 
LIMIT 0, '.$number.'
			';
		$stmt = $em->getConnection()->prepare($sql);
		$stmt->execute();
		$data = array();
		foreach($stmt->fetchAll() as $row) {
			$data[] = $this->find($row['product_id']);
		}
		return $data;
	}

	/**
	 * 查询好评最多的产品
	 * @param \Sylius\Bundle\TaxonomiesBundle\Model\TaxonInterface $taxon
	 * @param type $number
	 * @param type $loop
	 * @return type
	 */
	public function getMostCommentProducts(TaxonInterface $taxon, $number = 5, $loop = false)
	{
        $queryBuilder = $this->getQueryBuilder();
		$em = $queryBuilder->getEntityManager();
		$taxons = array();
		foreach($taxon->getChildren() as $sub) {
			$taxons[] = $sub->getId();
			if($loop) {
				foreach($sub->getChildren() as $s_sub) {
					$taxons[] = $s_sub->getId();
				}
			}
		}
		$ids = implode(',', $taxons);
		$sql = 'SELECT DISTINCT b.product, t . * , COUNT( b.product ) count
FROM sylius_comment b
LEFT JOIN sylius_product_taxon t ON b.product = t.product_id
WHERE t.taxon_id IN ('.$ids.')
	AND b.score > 2
GROUP BY b.product
ORDER BY count DESC 
LIMIT 0, '.$number.'
			';
		$stmt = $em->getConnection()->prepare($sql);
		$stmt->execute();
		$data = array();
		foreach($stmt->fetchAll() as $row) {
			$data[] = $this->find($row['product_id']);
		}
		return $data;
	}

	public function getOrders($product, $state = 1)
	{
        $queryBuilder = $this->getQueryBuilder();
		$em = $queryBuilder->getEntityManager();

		$sql = 'SELECT * FROM sylius_order_item oi LEFT JOIN sylius_variant v
			ON oi.sellable_id = v.id
			LEFT JOIN sylius_order o
			ON o.id = oi.order_id
			WHERE v.product_id = '.$product->getId().'
				AND o.order_status = '.$state.'
			';
		$stmt = $em->getConnection()->prepare($sql);
		$stmt->execute();
		$data = array();
		foreach($stmt->fetchAll() as $row) {
			$data[] = $row;
		}
		return $data;
	}
}
