<?php

namespace MooMoo\Platform\Bundle\ConditionBundle\Model;

trait ConditionAwareTrait
{
    /**
     * @var ConditionInterface[]
     */
    private $conditions = [];

    /**
     * @var ConditionInterface[]
     */
    private $lazyConditions = [];

    /**
     * @var ConditionInterface[]
     */
    private $notLazyConditions = [];
    
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
     * @return ConditionInterface[]
     */
    public function getLazyConditions()
    {
        return $this->lazyConditions;
    }

    /**
     * @return ConditionInterface[]
     */
    public function getNotLazyConditions()
    {
        return $this->notLazyConditions;
    }

    /**
     * @param ConditionInterface $condition
     */
    public function addCondition(ConditionInterface $condition)
    {
        $this->conditions[] = $condition;
        if ($condition->isLazy()) {
            $this->lazyConditions[] = $condition;
        } else {
            $this->notLazyConditions[] = $condition;
        }
    }
}
