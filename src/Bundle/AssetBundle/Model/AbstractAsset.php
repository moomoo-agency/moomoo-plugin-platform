<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Model;

use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareInterface;
use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareTrait;
use MooMoo\Platform\Bundle\KernelBundle\ParameterBag\ParameterBag;

abstract class AbstractAsset extends ParameterBag  implements AssetInterface, ConditionAwareInterface
{
    use ConditionAwareTrait;

    const HANDLE_FIELD = 'handle';
    const SOURCE_FIELD = 'source';
    const VERSION_FIELD = 'version';
    const CATEGORY_FIELD = 'category';
    const DEPENDENCIES_FIELD = 'dependencies';
    const ASSET_DATA_FIELD = 'data';

    /**
     * @return string
     */
    public function getHandle()
    {
        return $this->get(self::HANDLE_FIELD);
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->get(self::SOURCE_FIELD);
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return $this->get(self::DEPENDENCIES_FIELD, []);
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->get(self::VERSION_FIELD, false);
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->get(self::CATEGORY_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function getAssetData()
    {
        return $this->get(self::ASSET_DATA_FIELD, []);
    }

    /**
     * @inheritDoc
     */
    public function addAssetDataItem(AssetDataItemInterface $dataItem)
    {
        $assetData = $this->getAssetData();
        if (!in_array($dataItem, $assetData)) {
            $assetData[$dataItem->getKey()] = $dataItem;
            $this->set(self::ASSET_DATA_FIELD, $assetData);
        }

        return $this;
    }
}
