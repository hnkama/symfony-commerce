<?php

namespace Jiwen\BannerBundle\Form\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Jiwen\BannerBundle\Entity\BannerCategory;
use Doctrine\ORM\QueryBuilder;
use Lexik\Bundle\FormFilterBundle\Filter\ORM\Expr;

/**
 *
 * @author Zhili He <zhili850702@gmail.com, http://zhilihe.com/>
 */
class BannerFilterType extends AbstractType
{
	protected $em;

	public function __construct($em = null)
	{
		$this->em = $em;
	}
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$categories = $this->em->getRepository('JiwenBannerBundle:BannerCategory')->findAll();
		$choices = array();
		foreach($categories as $row) {
			$choices[$row->getId()] = $row->getName();
		}
        $builder
            ->add('category', 'filter_choice', array(
				'choices' => $choices,
				'label' => '分类',
				'apply_filter' => function (QueryBuilder $filterBuilder, Expr $expr, $field, array $values) {
					if (!empty($values['value'])) {
						$filterBuilder->andWhere($expr->eq($field, "'".$values['value']."'"));
					}
				},
			))
            ->add('startTime', 'filter_datetime', array(
				'label' => '开始时间',
				'date_widget'=> 'single_text',
				'apply_filter' => function (QueryBuilder $filterBuilder, Expr $expr, $field, array $values) {
					if (!empty($values['value'])) {
						$filterBuilder->andWhere($expr->gte($field, "'".$values['value']->format('Y-m-d H:i:s')."'"));
					}
				},
			))
            ->add('endTime', 'filter_datetime', array(
				'label' => '结束时间',
				'date_widget'=> 'single_text',
				'apply_filter' => function (QueryBuilder $filterBuilder, Expr $expr, $field, array $values) {
					if (!empty($values['value'])) {
						$filterBuilder->andWhere($expr->lte($field, "'".$values['value']->format('Y-m-d H:i:s')."'"));
					}
				},
			))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
            	'data_class' => 'Jiwen\BannerBundle\Entity\Banner',
				'csrf_protection'   => false,
            	'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
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