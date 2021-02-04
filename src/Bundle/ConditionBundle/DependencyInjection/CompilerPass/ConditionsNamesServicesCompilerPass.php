<?php

namespace MooMoo\Platform\Bundle\ConditionBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class ConditionsNamesServicesCompilerPass implements CompilerPassInterface
{
    const CONDITION_TAG = 'moomoo_condition';

    /**
     * @var array
     */
    private $conditions;

    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        $conditions = $container->findTaggedServiceIds(self::CONDITION_TAG);
        if (!$conditions) {
            return;
        }
        foreach ($conditions as $condition => $attributes) {
            $definition = $container->getDefinition($condition);
            $arguments = $definition->getArguments();
            if (!empty($arguments)) {
                $newDef = new ChildDefinition($condition);
                $container->setDefinition($arguments[0]['name'], $newDef);
            } else {
                $calls = $definition->getMethodCalls('setName');
                foreach ($calls as $call) {
                    if ($call[0] === 'setName') {
                        $newDef = new ChildDefinition($condition);
                        $container->setDefinition($call[1][0], $newDef);
                    }
                }
            }
        }
    }
}
