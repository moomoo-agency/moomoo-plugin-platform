<?php

namespace MooMoo\Platform\Bundle\CronBundle\Checker\CronCommand\Chain\Element;

use Exception;
use MooMoo\Platform\Bundle\CronBundle\Model\CronCommandInterface;

class BaseCronCommandCheckerChainElement extends AbstractCronCommandCheckerChainElement
{
    /**
     * @inheritDoc
     */
    public function checkCommand(CronCommandInterface $command)
    {
        if (!$command->getName()) {
            throw new Exception('name property of cron_command should not be empty');
        }
        if (strpos($command->getName(), ' ') !== false) {
            throw new Exception("name property of cron_command can't contain spaces");
        }
        if (sanitize_text_field($command->getName()) !== $command->getName()) {
            throw new Exception("cron_command name property did not pass 'sanitize_text_field'");
        }
        if (!$command->getTimestamp()) {
            throw new Exception('timestamp property of cron_command should not be empty');
        }
        if (!is_int($command->getTimestamp())) {
            throw new Exception('timestamp property of cron_command should be integer value');
        }
        if (!is_array($command->getArguments())) {
            throw new Exception('arguments property of cron_command should be array value');
        }

        return true;
    }
}
