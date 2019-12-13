<?php

namespace MooMoo\Platform\Bundle\ConditionBundle;

use MooMoo\Platform\Bundle\ConditionBundle\DependencyInjection\CompilerPass\ConditionsNamesServicesCompilerPass;
use MooMoo\Platform\Bundle\KernelBundle\Bundle\Bundle;
use MooMoo\Platform\Bundle\KernelBundle\DependencyInjection\CompilerPass\KernelCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ConditionBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            new KernelCompilerPass(
                'moomoo_condition',
                'moomoo_condition.registry.conditions',
                'addCondition'
            )
        );
        $container->addCompilerPass(new ConditionsNamesServicesCompilerPass());
    }
}
