<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * Default taxonomies to play with Sylius.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class LoadTaxonomiesData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $manager->persist($this->createTaxonomy('Category', array(
            '教会用品' => array('圣诗服', '桌 椅','板 凳','餐 具','丧 衣','奉献袋','饼 盘','杯 盘','圣经包','读经卡','祷告垫',),
			'图书音像' => array('图书'=>
				array(
'小说',
'传记',
'管理',
'工商',
'灵修',
'家庭',
'婚恋',
'主日学',
'青少年',
				)
				,
				'音像' => array(
'音乐',
'影视',
'软件',
'教育',
				)),
			'珠宝手表',
			'手机数码' => array('手机','圣经播放器'),
			'文具用品',
			'赞美乐器',
			'家居生活',
			'艺术收藏',
			'服饰鞋帽',
        )));

        $manager->persist($this->createTaxonomy('Brand', array(
            'SuperTees', 'Stickypicky', 'Mugland', 'Bookmania'
        )));

        $manager->flush();
    }

    /**
     * Create and save taxonomy with given taxons.
     *
     * @param string $name
     * @param array  $taxons
     */
    private function createTaxonomy($name, array $taxons, $taxon_parent = null, $is_root = true)
    {
		if($is_root) {
			$taxonomy = $this
				->getTaxonomyRepository()
				->createNew()
			;
        	$taxonomy->setName($name);
		}


        foreach ($taxons as $taxonName => $sub_taxons) {
			if(!is_array($sub_taxons)) {
				$taxonName = $sub_taxons;
			}
            $taxon = $this
                ->getTaxonRepository()
                ->createNew()
            ;

            $taxon->setName($taxonName);

			if(!$is_root) {
				$taxon_parent->addChild($taxon);
			} else {
            	$taxonomy->addTaxon($taxon);
			}
			if(is_array($sub_taxons)) {
				$this->createTaxonomy($name, $sub_taxons, $taxon, false);
			} 
            $this->setReference('Sylius.Taxon.'.$taxonName, $taxon);
        }

		if(isset($taxonomy)) {
        	$this->setReference('Sylius.Taxonomy.'.$name, $taxonomy);
        	return $taxonomy;
		}
    }

    public function getOrder()
    {
        return 5;
    }
}
