<?php

namespace MooMoo\Platform\Bundle\CronBundle\Hooks;

use MooMoo\Platform\Bundle\CronBundle\Checker\CronSchedule\CronScheduleCheckerInterface;
use MooMoo\Platform\Bundle\CronBundle\Model\CronSchedule;
use MooMoo\Platform\Bundle\CronBundle\Model\CronScheduleInterface;
use MooMoo\Platform\Bundle\HookBundle\Model\AbstractHook;
use MooMoo\Platform\Bundle\HookBundle\Model\HookInterface;

class CronSchedulesRegistrationHook extends AbstractHook
{
    /**
     * @var CronScheduleInterface[]
     */
    private $schedules = [];

    /**
     * @var CronScheduleCheckerInterface
     */
    private $checker;

    /**
     * @param CronScheduleCheckerInterface $checker
     * @return $this
     */
    public function setChecker(CronScheduleCheckerInterface $checker)
    {
        $this->checker = $checker;

        return $this;
    }

    /**
     * @param CronScheduleInterface $schedule
     * @return $this
     */
    public function addCronSchedule(CronScheduleInterface $schedule)
    {
        if ($this->checker) {
            if ($this->checker->check($schedule)) {
                $this->schedules[$schedule->getName()] = $schedule;
            }
        } else {
            $this->schedules[$schedule->getName()] = $schedule;
        }
    }

    /**
     * @inheritDoc
     */
    public function getType()
    {
        return HookInterface::FILTER_TYPE;
    }

    /**
     * @inheritDoc
     */
    public function getFunction()
    {
        $schedules = func_get_arg(0);
        foreach ($this->schedules as $name => $schedule) {
            $schedules[$name] = [
                CronSchedule::INTERVAL_FIELD => (int)$schedule->getInterval(),
                CronSchedule::DISPLAY_FIELD => esc_html__($schedule->getDisplay())
            ];
        }

        return $schedules;
    }
}