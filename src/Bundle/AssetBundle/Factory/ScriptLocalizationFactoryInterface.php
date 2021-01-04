<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Factory;

use MooMoo\Platform\Bundle\AssetBundle\Model\ScriptLocalizationInterface;

interface ScriptLocalizationFactoryInterface
{
    /**
     * @param string $object
     * @param string $property
     * @param array $data
     * @return ScriptLocalizationInterface
     */
    public static function create($object, $property, $data);
}
