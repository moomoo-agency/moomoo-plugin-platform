<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Model;

interface AssetInterface
{
    const FRONTEND_CATEGORY = 'frontend';
    const ADMIN_CATEGORY = 'admin';

    /**
     * @return bool
     */
    public function registerOnly();

    /**
     * @return string
     */
    public function getHandle();

    /**
     * @return string
     */
    public function getSource();

    /**
     * @return array
     */
    public function getDependencies();

    /**
     * @return string
     */
    public function getVersion();

    /**
     * @return string
     */
    public function getCategory();

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
