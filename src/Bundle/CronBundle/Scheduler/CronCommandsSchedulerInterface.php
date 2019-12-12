<?php

namespace MooMoo\Platform\Bundle\CronBundle\Scheduler;

use MooMoo\Platform\Bundle\CronBundle\Model\CronCommandInterface;

interface CronCommandsSchedulerInterface
{
    /**
     * @param CronCommandInterface[] $commands
     * @return void
     */
    public function scheduleCommands(array $commands);
}