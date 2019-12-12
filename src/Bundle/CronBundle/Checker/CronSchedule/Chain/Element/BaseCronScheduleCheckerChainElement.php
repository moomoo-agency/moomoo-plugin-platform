<?php

namespace MooMoo\Platform\Bundle\CronBundle\Checker\CronSchedule\Chain\Element;

use MooMoo\Platform\Bundle\CronBundle\Model\CronScheduleInterface;

class BaseCronScheduleCheckerChainElement extends AbstractCronScheduleCheckerChainElement
{
    /**
     * @inheritDoc
     */
    public function checkSchedule(CronScheduleInterface $schedule)
    {
        if (!$schedule->getName()) {
            throw new \Exception('There is no required property "name" for cron_schedule_interval');
        }
        if (strpos($schedule->getName(), ' ') !== false) {
            throw new \Exception("cron_schedule_interval name can't contain spaces");
        }
        if (sanitize_text_field($schedule->getName()) !== $schedule->getName()) {
            throw new \Exception("cron_schedule_interval name did not pass 'sanitize_text_field'");
        }
        if (!$schedule->getInterval()) {
            throw new \Exception('There is no required property "interval" for cron_schedule_interval');
        }
        if ((int)$schedule->getInterval() !== $schedule->getInterval()) {
            throw new \Exception("interval property of cron_schedule_interval should be integer value");
        }
        if (!$schedule->getDisplay()) {
            throw new \Exception('There is no required property "display" for cron_schedule_interval');
        }
        if (sanitize_text_field($schedule->getDisplay()) !== $schedule->getDisplay()) {
            throw new \Exception("cron_schedule_interval display did not pass 'sanitize_text_field'");
        }
        
        return true;
    }
}
