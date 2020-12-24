<?php

namespace MooMoo\Platform\Bundle\CronBundle\Checker\CronCommand\Chain\Element;

use Exception;
use MooMoo\Platform\Bundle\CronBundle\Model\CronCommandInterface;
use MooMoo\Platform\Bundle\CronBundle\Model\CronRecurrentCommandInterface;

class RecurrenceCronCommandCheckerChainElement extends AbstractCronCommandCheckerChainElement
{
    /**
     * @inheritDoc
     */
    public function checkCommand(CronCommandInterface $command)
    {
        if (!$command instanceof CronRecurrentCommandInterface) {
            return true;
        }
        $recurrence = $command->getRecurrence();
        if (!$recurrence) {
            throw new Exception('recurrence property of recurrent_cron_command should not be empty');
        }
        if (!in_array($recurrence, array_keys(wp_get_schedules()))) {
            throw new Exception(sprintf('there is no registered "%s" cron_schedule', $recurrence));
        }

        return true;
    }
}
