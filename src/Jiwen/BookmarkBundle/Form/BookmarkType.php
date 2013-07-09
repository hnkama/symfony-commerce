<?php

namespace Jiwen\BookmarkBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BookmarkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user')
            ->add('product')
            ->add('created')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jiwen\BookmarkBundle\Entity\Bookmark'
        ));
    }

    public function getName()
    {
        return 'jiwen_bookmarkbundle_bookmarktype';
    }
}
