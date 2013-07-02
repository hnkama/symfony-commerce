<?php

/*
* This file is part of the Sylius package.
*
* (c) Paweł Jędrzejewski
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Sylius\Bundle\CoreBundle\Form\Type;

use Sylius\Bundle\SalesBundle\Form\Type\OrderType as BaseOrderType;
use Symfony\Component\Form\FormBuilderInterface;
use Sylius\Bundle\CoreBundle\Entity\Order;

/**
 * Order form type.
 * We add two addresses to form, and that's all.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class OrderType extends BaseOrderType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        parent::buildForm($builder, $options);

        $builder
//            ->add('shippingAddress', 'sylius_address')
//            ->add('billingAddress', 'sylius_address')
            ->add('orderStatus', 'choice', array(
				'choices' => Order::$orderStatuses,
                'label'        => 'sylius.order.orderStatus'
			))
        ;
    }
}
