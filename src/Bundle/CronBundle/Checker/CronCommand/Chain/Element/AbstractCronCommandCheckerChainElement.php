<?php

namespace MooMoo\Platform\Bundle\CronBundle\Checker\CronCommand\Chain\Element;

use MooMoo\Platform\Bundle\CronBundle\Checker\CronCommand\CronCommandCheckerInterface;
use MooMoo\Platform\Bundle\CronBundle\Model\CronCommandInterface;

abstract class AbstractCronCommandCheckerChainElement implements CronCommandCheckerInterface
{
    /**
     * @var CronCommandCheckerInterface|null
     */
    protected $successor;

    /**
     * @param CronCommandCheckerInterface $checker
     */
    public function setSuccessor(CronCommandCheckerInterface $checker)
    {
        $this->successor = $checker;
    }

    /**
     * @return CronCommandCheckerInterface|null
     */
    protected function getSuccessor()
    {
        return $this->successor;
    }

    /**
     * @inheritDoc
     */
    public function check(CronCommandInterface $command)
    {
        $result = $this->checkCommand($command);
        
        if ($this->getSuccessor()) {
            return $this->getSuccessor()->check($command);
        } else {
            return $result;
        }
    }

    /**
     * @param CronCommandInterface $schedule
     * @return bool
     */
    abstract protected function checkCommand(CronCommandInterface $schedule);
}
