<?php

namespace MooMoo\Platform\Bundle\HookBundle\Registrator;

use MooMoo\Platform\Bundle\HookBundle\Model\HookInterface;

interface HooksRegistratorInterface
{
    /**
     * @param HookInterface[] $hooks
     */
    public function registerHooks(array $hooks);
}
