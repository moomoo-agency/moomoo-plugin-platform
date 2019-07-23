<?php

namespace MooMoo\Platform\Bundle\QueryBundle;

use MooMoo\Platform\Bundle\KernelBundle\Bundle\Bundle;
use MooMoo\Platform\Bundle\QueryBundle\DependencyInjection\CompilerPass\QueryCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class QueryBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new QueryCompilerPass());
    }
}
