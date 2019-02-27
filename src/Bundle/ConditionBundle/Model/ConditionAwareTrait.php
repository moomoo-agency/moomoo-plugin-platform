<?php

namespace MooMoo\Platform\Bundle\ConditionBundle\Model;

trait ConditionAwareTrait
{
    /**
     * @var ConditionInterface[]
     */
    private $conditions = [];
    
    /**
     * @return bool
     */
    public function hasConditions()
    {
        return !empty($this->conditions);
    }

    /**
     * @return ConditionInterface[]
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @param ConditionInterface $condition
     */
    public function addCondition(ConditionInterface $condition)
    {
        $this->conditions[] = $condition;
    }
}
