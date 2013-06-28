<?php

namespace Jiwen\BannerBundle\Form\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Jiwen\BannerBundle\Entity\BannerCategory;

/**
 *
 * @author Zhili He <zhili850702@gmail.com, http://zhilihe.com/>
 */
class BannerFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category')
            ->add('startTime')
            ->add('endTime')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
            	'data_class' => 'Jiwen\BannerBundle\Entity\Banner'
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'jiwen_banner_filter';
    }
}