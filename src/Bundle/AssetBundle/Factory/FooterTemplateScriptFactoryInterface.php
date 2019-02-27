<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Factory;

use MooMoo\Platform\Bundle\AssetBundle\Model\FooterTemplateScriptInterface;
use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionInterface;

interface FooterTemplateScriptFactoryInterface
{
    /**
     * @param string $type
     * @param string $templatePath
     * @param string|null $prefix
     * @param string|null $suffix
     * @param ConditionInterface[] $conditions
     * @return FooterTemplateScriptInterface
     */
    public static function create($type, $templatePath, $prefix = null, $suffix = null, $conditions = []);
}
