<?php


namespace MooMoo\Platform\Bundle\HookBundle\Model;

use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareInterface;
use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareTrait;
use MooMoo\Platform\Bundle\KernelBundle\ParameterBag\ParameterBag;

abstract class AbstractHook extends ParameterBag implements HookInterface, ConditionAwareInterface
{
    const TAG_FIELD = 'tag';
    const PRIORITY_FIELD = 'priority';
    const ACCEPTED_ARGS_FIELD = 'accepted_args';
    const INIT_HOOK_NAME_FIELD = 'init_hook';

    use ConditionAwareTrait;

    /**
     * @inheritDoc
     */
    public function getInitHookName()
    {
        return $this->get(self::INIT_HOOK_NAME_FIELD, 'init');
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return $this->get(self::TAG_FIELD);
    }

    /**
     * @return int
     */
    public function getAcceptedArgs()
    {
        return $this->get(self::ACCEPTED_ARGS_FIELD, 1);
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->get(self::PRIORITY_FIELD, 10);
    }
}
