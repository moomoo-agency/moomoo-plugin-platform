<?php

namespace MooMoo\Platform\Bundle\KernelBundle\EventDispatcher;

use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareInterface;
use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareTrait;

class ConditionAwareEventListener implements ConditionAwareInterface
{
    use ConditionAwareTrait;
}