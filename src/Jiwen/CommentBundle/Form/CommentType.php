<?php

namespace Jiwen\CommentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommentType extends AbstractType
{
	protected $product;
	protected $user;
	protected $myorder;

	public function __construct($product = null, $user = null, $myorder = null)
	{
		$this->product = $product;
		$this->user = $user;
		$this->myorder = $myorder;
	}
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comment', null, array(
				'label' => '评论内容'
			))
            ->add('score', null, array(
				'attr'=> array('style'=>'display:none;'),
				'label' => '评论分数'
			))
            ->add('created', null, array(
				'data' => new \DateTime(),
				'attr'=> array('style'=>'display:none;'),
				'label'=> ' ',
			))
            ->add('file', null, array(
				'label' => '晒单图片'
			))
			->add('myorder', null, array(
				'data' => $this->myorder,
				'attr'=> array('style'=>'display:none;'),
				'label'=> ' ',
			))
            ->add('product', null, array(
				'data' => $this->product,
				'attr'=> array('style'=>'display:none;'),
				'label'=> ' ',
			))
            ->add('user', null, array(
				'data' => $this->user,
				'attr'=> array('style'=>'display:none;'),
				'label'=> ' ',
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
