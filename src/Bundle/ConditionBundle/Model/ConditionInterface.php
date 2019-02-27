<?php

namespace MooMoo\Platform\Bundle\ConditionBundle\Model;

interface ConditionInterface
{
    /**
     * @return boolean
     */
    public function evaluate();
}
