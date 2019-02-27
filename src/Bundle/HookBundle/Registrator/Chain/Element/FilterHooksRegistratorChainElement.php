<?php

namespace MooMoo\Platform\Bundle\HookBundle\Registrator\Chain\Element;

use MooMoo\Platform\Bundle\HookBundle\Model\HookInterface;

class FilterHooksRegistratorChainElement extends AbstractHooksRegistratorChainElement
{
    /**
     * @inheritDoc
     */
    public function isApplicable(HookInterface $hook)
    {
        return $hook->getType() === HookInterface::FILTER_TYPE;
    }

    /**
     * @inheritDoc
     */
    public function register(HookInterface $hook)
    {
        add_filter($hook->getTag(), array($hook, 'getFunction'), $hook->getPriority(), $hook->getAcceptedArgs());
    }
}
