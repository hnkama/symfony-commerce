<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\WebBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Frontend homepage controller.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class HomepageController extends Controller
{

    /**
     * Store front page.
     *
     * @return Response
     */
    public function mainAction()
    {
        $theme = $this->container->getParameter('twig.theme', 'default');
        return $this->render('SyliusWebBundle:Frontend/' . $theme . '/Homepage:main.html.twig');
    }

    public function recommendAction()
    {
		$propertyRepository = $this->container->get('sylius.repository.property');
		$productPropertyRepository = $this->container->get('sylius.repository.product_property');

		$property = $propertyRepository->findOneBy(array('name' => '今日推荐'));

        $productsTodayRecommend = $productPropertyRepository->findBy(
            array('property' => $property->getId(), 'value' => '是'),
            array('id' => 'DESC'),
            6
        );
        return $this->render('SyliusWebBundle:Frontend/' . $this->container->getParameter('twig.theme', 'default') . '/Homepage:recommend.html.twig', array(
        'todayRecommend' => $productsTodayRecommend,
        ));
    }

}
