<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Model;

interface AssetInterface
{
    const FRONTEND_CATEGORY = 'frontend';
    const ADMIN_CATEGORY = 'admin';
    
    const SCRIPT_TYPE = 'script';
    const STYLE_TYPE = 'style';

    /**
     * @return string
     */
    public function getHandle();

    /**
     * @return string
     */
    public function getSource();

    /**
     * @return AssetInterface[]
     */
    public function getDependencies();

    /**
     * @return string
     */
    public function getVersion();

    /**
     * @return string|bool
     */
    public function getExtra();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getCategory();

    /**
     * @return AssetLocalizationInterface[]
     */
    public function getLocalizations();

    /**
     * @param AssetLocalizationInterface $localization
     * @return $this
     */
    public function addLocalization(AssetLocalizationInterface $localization);

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
