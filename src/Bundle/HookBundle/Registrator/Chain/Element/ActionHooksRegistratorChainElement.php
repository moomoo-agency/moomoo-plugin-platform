<?php

namespace MooMoo\Platform\Bundle\HookBundle\Registrator\Chain\Element;

use MooMoo\Platform\Bundle\HookBundle\Model\HookInterface;

class ActionHooksRegistratorChainElement extends AbstractHooksRegistratorChainElement
{
    /**
     * @inheritDoc
     */
    public function isApplicable(HookInterface $hook)
    {
        return $hook->getType() === HookInterface::ACTION_TYPE;
    }

    /**
     * @inheritDoc
     */
    public function register(HookInterface $hook)
    {
        add_action($hook->getTag(), [$hook, 'getFunction'], $hook->getPriority(), $hook->getAcceptedArgs());
    }
}
