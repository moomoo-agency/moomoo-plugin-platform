<?php

namespace MooMoo\Platform\Bundle\WpCliBundle\Registrator;

use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareInterface;
use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareTrait;
use MooMoo\Platform\Bundle\WpCliBundle\Model\WpCliCommandInterface;

class WpCliCommandsRegistrator implements WpCliCommandsRegistratorInterface, ConditionAwareInterface
{
    use ConditionAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function registerCommands(array $commands)
    {
        $evaluated = true;
        foreach ($this->getConditions() as $condition) {
            if ($condition->evaluate() === false) {
                $evaluated = false;
                break;
            }
        }
        if ($evaluated) {
            foreach ($commands as $command) {
                if ($command instanceof WpCliCommandInterface) {
                    \WP_CLI::add_command($command->getName(), [$command, 'getCallable'], $command->getArguments());
                }
            }
        }
    }
}
