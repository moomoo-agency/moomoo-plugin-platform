<?php

namespace MooMoo\Platform\Bundle\CronBundle\Scheduler\Chain\Element;

use MooMoo\Platform\Bundle\CronBundle\Model\CronCommandInterface;
use MooMoo\Platform\Bundle\CronBundle\Model\CronSingleCommandInterface;

class CronSingleCommandsSchedulerChainElement extends AbstractCronCommandsSchedulerChainElement
{
    /**
     * @inheritDoc
     */
    public function isApplicable(CronCommandInterface $command)
    {
        if ($command instanceof CronSingleCommandInterface) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function schedule(CronCommandInterface $command, $pluginBaseName)
    {
        add_action($command->getName(), [$command, 'execute']);
        add_action('activate_' . $pluginBaseName, function () use ($command) {
            wp_schedule_single_event($command->getTimestamp(), $command->getName(), $command->getArguments());
        });
    }
}