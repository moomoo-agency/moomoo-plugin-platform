<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Factory;

use MooMoo\Platform\Bundle\AssetBundle\Model\AssetLocalization;

class AssetLocalizationFactory implements AssetLocalizationFactoryInterface
{
    /**
     * @inheritDoc
     */
    public static function create($objectName, $propertyName, $propertyData)
    {
        return new AssetLocalization([
            AssetLocalization::OBJECT_NAME_FIELD => $objectName,
            AssetLocalization::PROPERTY_NAME_FIELD => $propertyName,
            AssetLocalization::PROPERTY_DATA_FIELD => $propertyData
        ]);
    }
}
