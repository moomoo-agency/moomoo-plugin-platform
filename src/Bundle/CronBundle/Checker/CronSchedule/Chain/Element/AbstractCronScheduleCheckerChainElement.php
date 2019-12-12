<?php

namespace MooMoo\Platform\Bundle\CronBundle\Checker\CronSchedule\Chain\Element;

use MooMoo\Platform\Bundle\CronBundle\Checker\CronSchedule\CronScheduleCheckerInterface;
use MooMoo\Platform\Bundle\CronBundle\Model\CronScheduleInterface;

abstract class AbstractCronScheduleCheckerChainElement implements CronScheduleCheckerInterface
{
    /**
     * @var CronScheduleCheckerInterface|null
     */
    protected $successor;

    /**
     * @param CronScheduleCheckerInterface $checker
     */
    public function setSuccessor(CronScheduleCheckerInterface $checker)
    {
        $this->successor = $checker;
    }

    /**
     * @return CronScheduleCheckerInterface|null
     */
    protected function getSuccessor()
    {
        return $this->successor;
    }

    /**
     * @inheritDoc
     */
    public function check(CronScheduleInterface $schedule)
    {
        $result = $this->checkSchedule($schedule);
        
        if ($this->getSuccessor()) {
            return $this->getSuccessor()->check($schedule);
        } else {
            return $result;
        }
    }

    /**
     * @param CronScheduleInterface $schedule
     * @return bool
     */
    abstract protected function checkSchedule(CronScheduleInterface $schedule);
}
