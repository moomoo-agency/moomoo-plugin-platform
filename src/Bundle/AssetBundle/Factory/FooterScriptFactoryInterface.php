<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Factory;

use MooMoo\Platform\Bundle\AssetBundle\Model\FooterScriptInterface;
use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionInterface;

interface FooterScriptFactoryInterface
{
    /**
     * @param array $arguments
     * @param ConditionInterface[] $conditions
     * @return FooterScriptInterface
     */
    public static function create(array $arguments, array $conditions = []);
}
