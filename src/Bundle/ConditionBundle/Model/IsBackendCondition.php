<?php

namespace MooMoo\Platform\Bundle\ConditionBundle\Model;

class IsBackendCondition extends AbstractCondition
{
    /**
     * @inheritDoc
     */
    protected function getResult()
    {
        return is_admin();
    }
}
