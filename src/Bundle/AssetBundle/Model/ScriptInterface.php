<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Model;

interface ScriptInterface extends AssetInterface
{
    /**
     * @return bool|null
     */
    public function isInFooter();

    /**
     * @return ScriptLocalizationInterface[]
     */
    public function getLocalizations();

    /**
     * @param ScriptLocalizationInterface $localization
     * @return $this
     */
    public function addLocalization(ScriptLocalizationInterface $localization);
}