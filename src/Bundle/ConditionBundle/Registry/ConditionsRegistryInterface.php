<?php

namespace MooMoo\Platform\Bundle\ConditionBundle\Registry;

use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionInterface;

interface ConditionsRegistryInterface
{
    /**
     * @return ConditionInterface[]
     */
    public function getConditions();

    /**
     * @param string $name
     * @return ConditionInterface
     */
    public function getCondition($name);

    /**
     * @param string $name
     * @return bool
     */
    public function hasCondition($name);
}
