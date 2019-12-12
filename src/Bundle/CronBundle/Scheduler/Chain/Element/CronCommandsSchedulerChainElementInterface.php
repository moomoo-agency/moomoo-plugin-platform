<?php

namespace MooMoo\Platform\Bundle\CronBundle\Scheduler\Chain\Element;

use MooMoo\Platform\Bundle\CronBundle\Model\CronCommandInterface;

interface CronCommandsSchedulerChainElementInterface
{
    /**
     * @param CronCommandInterface $command
     * @return bool
     */
    public function isApplicable(CronCommandInterface $command);

    /**
     * @param CronCommandInterface $command
     * @param string $pluginBaseName
     */
    public function schedule(CronCommandInterface $command, $pluginBaseName);
}
