<?php

namespace MooMoo\Platform\Bundle\ConditionBundle\Registry;

use Builderius\Bundle\CategoryBundle\Model\BuilderiusCategoryInterface;
use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionInterface;

class BuilderiusCategoriesRegistry implements ConditionsRegistryInterface
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
     * {@inheritdoc}
     */
    public function getCategories()
    {
        return $this->conditions;
    }

    /**
     * {@inheritdoc}
     */
    public function getCategory($name)
    {
        if ($this->hasCategory($name)) {
            return $this->categories[$name];
        }
        
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function hasCategory($name)
    {
        if (isset($this->categories[$name])) {
            return true;
        }

        return false;
    }
}
