<?php

namespace Jiwen\CommentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comment')
            ->add('score')
            ->add('created')
            ->add('path')
            ->add('product')
            ->add('user')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jiwen\CommentBundle\Entity\Comment'
        ));
    }

    public function getName()
    {
        return 'jiwen_commentbundle_commenttype';
    }
}
