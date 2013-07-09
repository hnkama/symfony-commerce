<?php

namespace Jiwen\CommentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommentType extends AbstractType
{
	protected $product;
	protected $user;
	public function __construct($product = null, $user = null)
	{
		$this->product = $product;
		$this->user = $user;
	}
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comment')
            ->add('score')
            ->add('created', null, array(
				'data' => new \DateTime(),
			))
            ->add('path')
            ->add('product', null, array(
				'data' => $this->product,
			))
            ->add('user', null, array(
				'data' => $this->user,
			))
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
