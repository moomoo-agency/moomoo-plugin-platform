<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Factory;

use MooMoo\Platform\Bundle\AssetBundle\Model\AssetLocalizationInterface;

interface AssetLocalizationFactoryInterface
{
    /**
     * @param string $object
     * @param string $property
     * @param array $data
     * @return AssetLocalizationInterface
     */
    public static function create($object, $property, $data);
}
