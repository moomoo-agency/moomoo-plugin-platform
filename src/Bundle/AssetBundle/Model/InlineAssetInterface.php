<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Model;

interface InlineAssetInterface
{
    const FRONTEND_CATEGORY = 'frontend';
    const ADMIN_CATEGORY = 'admin';

    /**
     * @return string
     */
    public function getType();

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type);

    /**
     * @return string
     */
    public function getTagType();

    /**
     * @param string $tagType
     * @return $this
     */
    public function setTagType($tagType);

    /**
     * @return string
     */
    public function getContent();

    /**
     * @param string $content
     * @return $this
     */
    public function setContent($content);

    /**
     * @return string|null
     */
    public function getId();

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getCategory();

    /**
     * @param string $category
     * @return $this
     */
    public function setCategory($category);

    /**
     * @return array
     */
    public function getDependencies();

    /**
     * @param array $dependencies
     * @return $this
     */
    public function setDependencies(array $dependencies);

    /**
     * @return AssetDataItemInterface[]
     */
    public function getAssetData();

    /**
     * @param AssetDataItemInterface $dataItem
     * @return $this
     */
    public function addAssetDataItem(AssetDataItemInterface $dataItem);
}
