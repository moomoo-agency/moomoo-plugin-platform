<?php

namespace MooMoo\Platform\Bundle\CronBundle\Checker\CronSchedule;

use MooMoo\Platform\Bundle\CronBundle\Model\CronScheduleInterface;

interface CronScheduleCheckerInterface
{
    /**
     * @param CronScheduleInterface $schedule
     * @return boolean
     */
    public function check(CronScheduleInterface $schedule);
}
