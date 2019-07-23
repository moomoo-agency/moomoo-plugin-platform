<?php

namespace MooMoo\Platform\Bundle\ConditionBundle\Model;

interface ConditionInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * @param ConditionInterface $condition
     * @return $this
     */
    public function addDependOnCondition(ConditionInterface $condition);

    /**
     * @param ConditionInterface[] $conditions
     * @return $this
     */
    public function setDependOnConditions(array $conditions);

    /**
     * @return ConditionInterface[]
     */
    public function getDependOnConditions();
    
    /**
     * @return boolean
     */
    public function evaluate();
}
