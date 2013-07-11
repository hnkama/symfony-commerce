<?php

namespace Jiwen\BookmarkBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BookmarkType extends AbstractType
{

	protected $user;

	public function __construct()
	{
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
				->add('user', 'hidden', array(
					'data' => $this->user
				))
				->add('product','text')
				->add('created', null, array(
					'data' => new \DateTime(),
					'attr' => array('style' => 'display:none;')
				))
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
		return 'jiwen_bookmark';
	}

}
