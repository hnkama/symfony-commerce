<?php

namespace Jiwen\BannerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Jiwen\BannerBundle\Entity\BannerCategory;

class BannerCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('target', 'choice', array(
				'choices' => BannerCategory::$targets
			))
            ->add('width')
            ->add('height')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jiwen\BannerBundle\Entity\BannerCategory'
        ));
    }

    public function getName()
    {
        return 'jiwen_bannerbundle_bannercategorytype';
    }
}
