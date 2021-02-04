<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Factory;

use MooMoo\Platform\Bundle\AssetBundle\Model\InlineAsset;

class InlineAssetFactory implements InlineAssetFactoryInterface
{
    /**
     * @inheritDoc
     */
    public static function create(array $arguments, array $conditions = [])
    {
        $script = new InlineAsset($arguments);
        if (!empty($conditions)) {
            foreach ($conditions as $condition) {
                $script->addCondition($condition);
            }
        }

        return $script;
    }
}
