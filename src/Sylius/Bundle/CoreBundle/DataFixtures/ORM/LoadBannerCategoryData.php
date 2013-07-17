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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Jiwen\BannerBundle\Entity\BannerCategory;
use Jiwen\BannerBundle\Form\BannerType;
use Jiwen\BannerBundle\Form\Filter\BannerFilterType;

/**
 * Default zone fixtures.
 *
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */
class LoadBannerCategoryData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {

		$banner = new BannerCategory();
		$banner->setName('头部banner');
		$banner->setDescription('页面头部滚动banner');
		$banner->setTarget('_blank');
		$banner->setWidth(339);
		$banner->setHeight(68);
		$this->setReference('Jiwen.Banner.Top', $banner);
        $manager->persist($banner);

		$banner = new BannerCategory();
		$banner->setName('首页导航分类图片banner');
		$banner->setDescription('导航弹出菜单的二级分类里面的图片banner');
		$banner->setTarget('_blank');
		$banner->setWidth(410);
		$banner->setHeight(333);
		$this->setReference('Jiwen.Banner.Nav', $banner);
        $manager->persist($banner);

		$banner = new BannerCategory();
		$banner->setName('首页滚动大图');
		$banner->setDescription('首页的滚动大图banner');
		$banner->setWidth(1020);
		$banner->setHeight(382);
		$this->setReference('Jiwen.Banner.Slide', $banner);
        $manager->persist($banner);

		$banner = new BannerCategory();
		$banner->setName('首页滚动banner下面的banner');
		$banner->setDescription('首页滚动banner下面的banner');
		$banner->setTarget('_blank');
		$banner->setWidth(155);
		$banner->setHeight(181);
		$this->setReference('Jiwen.Banner.Sub', $banner);
        $manager->persist($banner);

        $manager->flush();
    }


    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 2;
    }
}
