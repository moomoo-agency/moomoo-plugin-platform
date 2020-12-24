<?php

namespace MooMoo\Platform\Bundle\CronBundle\Registry;

use Exception;
use MooMoo\Platform\Bundle\CronBundle\Checker\CronCommand\CronCommandCheckerInterface;
use MooMoo\Platform\Bundle\CronBundle\Model\CronCommandInterface;

class CronCommandsRegistry implements CronCommandsRegistryInterface
{
    /**
     * @var CronCommandInterface[]
     */
    protected $commands = [];

    /**
     * @var CronCommandCheckerInterface
     */
    private $checker;

    /**
     * @param CronCommandCheckerInterface $checker
     */
    public function __construct(CronCommandCheckerInterface $checker)
    {
        $this->checker = $checker;
    }

    /**
     * @param CronCommandInterface $command
     * @throws Exception
     */
    public function addCronCommand(CronCommandInterface $command)
    {
        if ($this->checker->check($command)) {
            if (isset($this->commands[$command->getName()])) {
                throw new Exception(sprintf('CronCommand with name "%s" already exists', $command->getName()));
            }
            $this->commands[$command->getName()] = $command;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCronCommands()
    {
        return $this->commands;
    }

    /**
     * {@inheritdoc}
     */
    public function getCronCommand($name)
    {
        if ($this->hasCronCommand($name)) {
            return $this->commands[$name];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function hasCronCommand($name)
    {
        if (isset($this->commands[$name])) {
            return true;
        }

        return false;
    }
}
