<?php

namespace MooMoo\Platform\Bundle\HookBundle\Registrator\Chain\Element;

use MooMoo\Platform\Bundle\HookBundle\Model\HookInterface;

interface HooksRegistratorChainElementInterface
{
    /**
     * @param HookInterface $hook
     * @return bool
     */
    public function isApplicable(HookInterface $hook);

    /**
     * @param HookInterface $hook
     */
    public function register(HookInterface $hook);
}
