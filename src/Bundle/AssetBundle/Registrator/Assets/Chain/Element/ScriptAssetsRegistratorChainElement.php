<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Registrator\Assets\Chain\Element;

use MooMoo\Platform\Bundle\AssetBundle\Model\AssetInterface;
use MooMoo\Platform\Bundle\AssetBundle\Model\AssetLocalizationInterface;
use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareInterface;

class ScriptAssetsRegistratorChainElement extends AbstractAssetsRegistratorChainElement
{
    /**
     * @inheritDoc
     */
    public function isApplicable(AssetInterface $asset)
    {
        return $asset->getType() === AssetInterface::SCRIPT_TYPE;
    }

    /**
     * @inheritDoc
     */
    public function register(AssetInterface $asset)
    {
        wp_enqueue_script(
            $asset->getHandle(),
            $this->pathProvider->getAssetPath($asset),
            $asset->getDependencies(),
            $asset->getVersion() ? : '1.0.0',
            $asset->getExtra() ? : true
        );
        if (!empty($asset->getLocalizations())) {
            $params = $this->transformLocalizations($asset->getLocalizations());
            foreach ($params as $objectName => $data) {
                wp_localize_script($asset->getHandle(), $objectName, $data);
            }
        }
        if (!empty($asset->getAssetData())) {
            foreach ($asset->getAssetData() as $dataItem) {
                wp_script_add_data($asset->getHandle(), $dataItem->getKey(), $dataItem->getValue());
            }
        }
    }
    
    /**
     * @param AssetLocalizationInterface[] $localizations
     * @return array
     */
    private function transformLocalizations(array $localizations)
    {
        $params = [];
        foreach ($localizations as $localization) {
            if ($localization instanceof ConditionAwareInterface && $localization->hasConditions()) {
                $evaluated = true;
                foreach ($localization->getConditions() as $condition) {
                    if ($condition->evaluate() === false) {
                        $evaluated = false;
                        break;
                    }
                }
                if (!$evaluated) {
                    continue;
                }
                $params[$localization->getObjectName()][$localization->getPropertyName()] =
                    $localization->getPropertyData();
            } else {
                $params[$localization->getObjectName()][$localization->getPropertyName()] =
                    $localization->getPropertyData();
            }
        }

        return $params;
    }
}
