<?php

/*
 * Used for get entity manager
 * $em = JiwenGeneralBundle::getContainer()->get('doctrine')->getEntityManager('default');
 */

namespace Jiwen\GeneralBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class JiwenGeneralBundle extends Bundle
{
	private static $containerInstance = null; 

    public function setContainer(\Symfony\Component\DependencyInjection 
\ContainerInterface $container = null) 
    { 
        parent::setContainer($container); 
        self::$containerInstance = $container; 
    } 

    public static function getContainer() 
    { 
        return self::$containerInstance; 
    } 
}
