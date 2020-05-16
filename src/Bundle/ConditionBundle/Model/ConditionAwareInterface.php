<?php

namespace MooMoo\Platform\Bundle\ConditionBundle\Model;

interface ConditionAwareInterface
{
    /**
     * @return bool
     */
    public function hasConditions();

    /**
     * @return ConditionInterface[]
     */
    public function getConditions();

    /**
     * @return ConditionInterface[]
     */
    public function getLazyConditions();

    /**
     * @return ConditionInterface[]
     */
    public function getNotLazyConditions();
}
