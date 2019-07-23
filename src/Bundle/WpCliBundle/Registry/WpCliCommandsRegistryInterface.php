<?php

namespace MooMoo\Platform\Bundle\WpCliBundle\Registry;

use MooMoo\Platform\Bundle\WpCliBundle\Model\WpCliCommandInterface;

interface WpCliCommandsRegistryInterface
{
    /**
     * @return WpCliCommandInterface[]
     */
    public function getCommands();

    /**
     * @param string $name
     * @return WpCliCommandInterface|null
     */
    public function getCommand($name);

    /**
     * @param string $name
     * @return bool
     */
    public function hasCommand($name);
}
