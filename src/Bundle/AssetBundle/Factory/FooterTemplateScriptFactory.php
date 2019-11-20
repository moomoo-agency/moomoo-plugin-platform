<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Factory;

use MooMoo\Platform\Bundle\AssetBundle\Model\FooterTemplateScript;

class FooterTemplateScriptFactory implements FooterTemplateScriptFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public static function create($type, $templatePath, $prefix = null, $suffix = null, $conditions = [])
    {
        $script = new FooterTemplateScript($type, $templatePath, $prefix, $suffix);
        if (!empty($conditions)) {
            foreach ($conditions as $condition) {
                $script->addCondition($condition);
            }
        }
        
        return $script;
    }
}
