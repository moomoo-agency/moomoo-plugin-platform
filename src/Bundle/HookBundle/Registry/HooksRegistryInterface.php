<?php

namespace MooMoo\Platform\Bundle\HookBundle\Registry;

use MooMoo\Platform\Bundle\HookBundle\Model\HookInterface;

interface HooksRegistryInterface
{
    /**
     * @return HookInterface[]
     */
    public function getHooks();
}
