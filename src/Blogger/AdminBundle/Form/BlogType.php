<?php
namespace Blogger\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Blogger\BlogBundle\Entity\Classify;
/*
 *blog表单格式控制类
 * */
class BlogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title','text', array('max_length'=>30));
        $builder->add('important')
        		->add('classify')
        		->add('created', 'datetime', array(
        				'widget' => 'single_text',
    					'data' => new \DateTime("now")
					));
        $builder->add('is_open');
        $builder->add('author','text', array('max_length'=>30));
        $builder->add('keywords','text', array('max_length'=>30));
        $builder->add('link','url', array('max_length'=>50));
        $builder->add('author_email', 'email', array('max_length'=>40));
        $builder->add('description', 'textarea', array('max_length'=>120));
        $builder->add('content', 'textarea', array('max_length'=>5000));
    }
    public function getName()
    {
        return 'contact';
    }
}
?>