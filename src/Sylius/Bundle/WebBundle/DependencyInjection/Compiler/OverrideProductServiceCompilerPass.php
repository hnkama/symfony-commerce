<?php

/**
 *
 * @author Zhili He <zhili850702@gmail.com, http://zhilihe.com/>
 */
namespace Sylius\Bundle\WebBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OverrideProductServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('sylius.controller.product');
		$args = array('sylius', 'product', 'SyliusWebBundle');
		$definition->setArguments($args);

    }
}