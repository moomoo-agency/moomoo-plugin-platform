<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Registrator\Assets\Chain\Element;

use MooMoo\Platform\Bundle\AssetBundle\Model\AssetInterface;
use MooMoo\Platform\Bundle\AssetBundle\Model\ScriptInterface;
use MooMoo\Platform\Bundle\AssetBundle\Model\ScriptLocalizationInterface;
use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareInterface;

class ScriptAssetsRegistratorChainElement extends AbstractAssetsRegistratorChainElement
{
    /**
     * @inheritDoc
     */
    public function isApplicable(AssetInterface $asset)
    {
        return $asset instanceof ScriptInterface;
    }

    /**
     * @inheritDoc
     */
    public function register(AssetInterface $asset)
    {
        /** @var ScriptInterface $asset */
        if ($asset->registerOnly()) {
            wp_register_script(
                $asset->getHandle(),
                $this->pathProvider->getAssetPath($asset),
                $asset->getDependencies(),
                $asset->getVersion() ?: false,
                $asset->isInFooter() ?: false
            );
        } else {
            wp_enqueue_script(
                $asset->getHandle(),
                $this->pathProvider->getAssetPath($asset),
                $asset->getDependencies(),
                $asset->getVersion() ?: false,
                $asset->isInFooter() ?: false
            );
        }
        if (!empty($asset->getLocalizations())) {
            $params = $this->transformLocalizations($asset->getLocalizations());
            foreach ($params as $objectName => $data) {
                wp_localize_script($asset->getHandle(), $objectName, $data);
            }
        }
        if (!empty($asset->getAssetData())) {
            $groupedData = [];
            foreach ($asset->getAssetData() as $dataItem) {
                $groupedData[$dataItem->getGroup()][$dataItem->getKey()] = $dataItem->getValue();
            }
            foreach ($groupedData as $group => $values) {
                wp_script_add_data($asset->getHandle(), $group, $values);
            }
        }
    }

    /**
     * @param ScriptLocalizationInterface[] $localizations
     * @return array
     */
    private function transformLocalizations(array $localizations)
    {
        $params = [];
        /** @var ScriptLocalizationInterface $localization */
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
