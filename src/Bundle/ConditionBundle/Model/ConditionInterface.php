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
     * @return bool
     */
    public function isLazy();

    /**
     * @param bool $lazy
     * @return $this
     */
    public function setLazy($lazy = false);

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description);

    /**
     * @param ConditionInterface $condition
     * @throws \Exception
     * @return $this
     */
    public function addDependOnCondition(ConditionInterface $condition);

    /**
     * @param ConditionInterface[] $conditions
     * @throws \Exception
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
