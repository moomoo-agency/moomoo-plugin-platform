<?php

namespace MooMoo\Platform\Bundle\CronBundle\Registry;

use MooMoo\Platform\Bundle\CronBundle\Model\CronCommandInterface;

interface CronCommandsRegistryInterface
{
    /**
     * @return CronCommandInterface[]
     */
    public function getCronCommands();

    /**
     * @param string $name
     * @return CronCommandInterface
     */
    public function getCronCommand($name);

    /**
     * @param string $name
     * @return bool
     */
    public function hasCronCommand($name);
}