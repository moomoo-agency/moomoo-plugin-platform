<?php

namespace MooMoo\Platform\Bundle\ConditionBundle\Registry;

use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionInterface;

class ConditionsRegistry implements ConditionsRegistryInterface
{
    /**
     * @var ConditionInterface[]
     */
    protected $conditions = [];

    /**
     * @param ConditionInterface $condition
     * @throws \Exception
     */
    public function addCondition(ConditionInterface $condition)
    {
        if (isset($this->conditions[$condition->getName()])) {
            throw new \Exception(sprintf('Condition with name "%s" already exists', $condition->getName()));
        }
        $this->conditions[$condition->getName()] = $condition;
    }

    /**
     * @inheritDoc
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @inheritDoc
     */
    public function getCondition($name)
    {
        if ($this->hasCondition($name)) {
            return $this->conditions[$name];
        }
        
        return null;
    }

    /**
     * @inheritDoc
     */
    public function hasCondition($name)
    {
        if (isset($this->conditions[$name])) {
            return true;
        }

        return false;
    }
}
