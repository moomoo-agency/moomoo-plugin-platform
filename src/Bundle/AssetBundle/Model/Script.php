<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Model;

class Script extends AbstractAsset implements ScriptInterface
{
    const IN_FOOTER_FIELD = 'inFooter';
    const LOCALIZATIONS = 'localizations';

    /**
     * @inheritDoc
     */
    public function isInFooter()
    {
        return $this->get(self::IN_FOOTER_FIELD, false);
    }

    /**
     * @inheritDoc
     */
    public function getLocalizations()
    {
        return $this->get(self::LOCALIZATIONS, []);
    }

    /**
     * @inheritDoc
     */
    public function addLocalization(ScriptLocalizationInterface $localization)
    {
        $localizations = $this->getLocalizations();
        if (!in_array($localization, $localizations)) {
            $localizations[$localization->getPropertyName()] = $localization;
            $this->set(self::LOCALIZATIONS, $localizations);
        }

        return $this;
    }
}