<?php

namespace Jiwen\BannerBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Jiwen\GeneralBundle\JiwenGeneralBundle;

/**
 * BannerCategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BannerRepository extends EntityRepository
{

	/**
	 * The taxonomy menu image banner
	 * 
	 * @param \Sylius\Bundle\TaxonomiesBundle\Model\Taxon $taxon
	 * @return string
	 */
	public function findOneMenuBanner(\Sylius\Bundle\TaxonomiesBundle\Model\Taxon $taxon)
	{
		$banner = '';
		$entity = $this->findOneByTaxon($taxon->getId());
		if ($entity) {
			$base_path = JiwenGeneralBundle::getContainer()->get('request')->getBasePath();
			$banner = '<img src="'.$base_path.'/uploads/documents/'.$entity->getPath().'" border="0" usemap="#Map'.$entity->getId().'" class="smw-img1" width="410" height="333" style="right:0; bottom:20px;">
                        <map name="Map'.$entity->getId().'" id="Map'.$entity->getId().'">
                          <area shape="rect" coords="57,18,397,319" href="'.$entity->getLink().'" alt="" target="">
                        </map>';
		}
		return $banner;
	}

    public function findTopBanner($category, $limit)
    {
		$qb = $this->createQueryBuilder('q');
		$today = new \DateTime();
		return $qb
				->andWhere('q.category = :category')
				->andWhere($qb->expr()->lte('q.startTime', ':now'))
				->andWhere($qb->expr()->gte('q.endTime', ':now'))
				->setMaxResults($limit)
				->setParameter('category', $category)
				->setParameter('now', $today->format('Y-m-d H:m:s'))
				->orderBy('q.id', 'DESC')
				->getQuery()
				->getResult()
				;
    }

}

