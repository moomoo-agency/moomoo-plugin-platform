<?php

namespace MooMoo\Platform\Bundle\HookBundle\Model;

abstract class AbstractFilter extends AbstractHook implements FilterInterface
{
    const RETURN_ARGUMENT_ON_FAILED_CONDITIONS = 'return_argument_on_failed_conditions';

    /**
     * @inheritDoc
     */
    public function getType()
    {
        return self::FILTER_TYPE;
    }

    /**
     * @inheritDoc
     */
    public function returnOnFailedConditions(array $args)
    {
        $number = $this->get(self::RETURN_ARGUMENT_ON_FAILED_CONDITIONS, 0);

        return $args[$number];
    }
}