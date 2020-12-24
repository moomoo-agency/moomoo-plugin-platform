<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Model;

use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareInterface;
use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareTrait;
use MooMoo\Platform\Bundle\KernelBundle\ParameterBag\ParameterBag;

class InlineAsset extends ParameterBag implements InlineAssetInterface, ConditionAwareInterface
{
    use ConditionAwareTrait;

    const TYPE_FIELD = 'type';
    const TAG_TYPE_FIELD = 'tagType';
    const ID_FIELD = 'id';
    const CONTENT_FIELD = 'content';
    const DEPENDENCIES_FIELD = 'dependencies';
    const ASSET_DATA_FIELD = 'assetData';

    /**
     * @inheritDoc
     */
    public function getType()
    {
        return $this->get(self::TYPE_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function setType($type)
    {
        $this->set(self::TYPE_FIELD, $type);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTagType()
    {
        return $this->get(self::TAG_TYPE_FIELD, 'text/javascript');
    }

    /**
     * @inheritDoc
     */
    public function setTagType($tagType)
    {
        $this->set(self::TAG_TYPE_FIELD, $tagType);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getContent()
    {
        return $this->get(self::CONTENT_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function setContent($content)
    {
        $this->set(self::CONTENT_FIELD, $content);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->get(self::ID_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function setId($id)
    {
        $this->set(self::ID_FIELD, $id);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return $this->get(self::DEPENDENCIES_FIELD, []);
    }

    /**
     * @inheritDoc
     */
    public function setDependencies(array $dependencies)
    {
        $this->set(self::DEPENDENCIES_FIELD, $dependencies);

        return $this;
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
            $this->set(self::ASSET_DATA_FIELD, $dataItem);
        }

        return $this;
    }
}
