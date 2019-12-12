<?php

namespace MooMoo\Platform\Bundle\CronBundle\Checker\CronCommand;

use MooMoo\Platform\Bundle\CronBundle\Model\CronCommandInterface;

interface CronCommandCheckerInterface
{
    /**
     * @param CronCommandInterface $command
     * @return boolean
     */
    public function check(CronCommandInterface $command);
}
