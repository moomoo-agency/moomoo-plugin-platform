<?php

namespace MooMoo\Platform\Bundle\CronBundle\Scheduler\Chain\Element;

use MooMoo\Platform\Bundle\CronBundle\Model\CronCommandInterface;
use MooMoo\Platform\Bundle\CronBundle\Model\CronRecurrentCommandInterface;

class CronRecurrentCommandsSchedulerChainElement extends AbstractCronCommandsSchedulerChainElement
{
    /**
     * @inheritDoc
     */
    public function isApplicable(CronCommandInterface $command)
    {
        if ($command instanceof CronRecurrentCommandInterface) {
            return true;
        }
        
        return false;
    }

    /**
     * @param CronCommandInterface|CronRecurrentCommandInterface $command
     * @param string $pluginBaseName
     */
    public function schedule(CronCommandInterface $command, $pluginBaseName)
    {
        add_action($command->getName(), [$command, 'execute']);
        add_action('activate_' . $pluginBaseName, function () use ($command) {
            wp_schedule_event(
                $command->getTimestamp(),
                $command->getRecurrence(),
                $command->getName(),
                $command->getArguments()
            );
        });
        add_action('deactivate_' . $pluginBaseName, function () use ($command) {
            wp_clear_scheduled_hook($command->getName());
        });
    }
}