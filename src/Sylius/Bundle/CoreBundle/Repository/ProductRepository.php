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
    public function createByTaxonPaginator(TaxonInterface $taxon, $maxResults = null)
    {
        $queryBuilder = $this->getCollectionQueryBuilder();

        $queryBuilder
            ->innerJoin('product.taxons', 'taxon')
            ->andWhere('taxon = :taxon')
            ->setParameter('taxon', $taxon)
        ;
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
        $this->applySorting($queryBuilder, array('createdAt'=>'DESC', 'saleQuantity'=>'DESC'));
		return $queryBuilder->getQuery()->getResult();
    }

    public function getByPropery(TaxonInterface $taxon, $property, $value, $number = 5)
    {
        $queryBuilder = $this->getQueryBuilder();
		$em = $queryBuilder->getEntityManager();

		$sql = 'SELECT * FROM sylius_product_property pp LEFT JOIN sylius_product_taxon pt 
			ON pp.product_id = pt.product_id
			WHERE pp.property_id = '.$property->getId().'
				AND pp.value = 1
				AND pt.taxon_id = '.$taxon->getId().'
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
}
