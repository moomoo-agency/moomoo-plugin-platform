<?php

namespace MooMoo\Platform\Bundle\QueryBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class QueryCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        //$container->set('moomoo_query.wp_the_query', $GLOBALS['wp_the_query']);
        //$container->set('moomoo_query.wp_query', $GLOBALS['wp_query']);
    }
}
