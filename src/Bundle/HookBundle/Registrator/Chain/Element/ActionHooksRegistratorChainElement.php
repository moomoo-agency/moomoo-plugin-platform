<?php

namespace MooMoo\Platform\Bundle\HookBundle\Registrator\Chain\Element;

use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareInterface;
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
        add_action(
            $hook->getTag(),
            function () use ($hook) {
                if ($hook instanceof ConditionAwareInterface) {
                    $evaluated = true;
                    foreach ($hook->getLazyConditions() as $condition) {
                        if ($condition->evaluate() === false) {
                            $evaluated = false;
                            break;
                        }
                    }
                    if ($evaluated) {
                        return call_user_func_array([$hook, 'getFunction'], func_get_args());
                    }
                } else {
                    return call_user_func_array([$hook, 'getFunction'], func_get_args());
                }
            },
            $hook->getPriority(),
            $hook->getAcceptedArgs()
        );
    }
}
