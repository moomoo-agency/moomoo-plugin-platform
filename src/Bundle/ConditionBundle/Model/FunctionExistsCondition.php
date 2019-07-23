<?php

namespace MooMoo\Platform\Bundle\ConditionBundle\Model;

class FunctionExistsCondition extends AbstractCondition
{
    const FUNCTION_FIELD = 'function';

    /**
     * @inheritDoc
     */
    protected function getResult()
    {
        return function_exists($this->get(self::FUNCTION_FIELD));
    }
}
