<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Registrator\InlineAssets\Chain\Element;

use MooMoo\Platform\Bundle\AssetBundle\Event\InlineAssetsContainingEvent;
use MooMoo\Platform\Bundle\AssetBundle\Formatter\HtmlAttributesFormatter;
use MooMoo\Platform\Bundle\AssetBundle\Model\InlineAssetInterface;
use MooMoo\Platform\Bundle\AssetBundle\Registrator\InlineAssets\InlineAssetsRegistratorInterface;
use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class AbstractInlineAssetsRegistratorChainElement implements
    InlineAssetsRegistratorInterface,
    InlineAssetsRegistratorChainElementInterface
{
    const ASSET_REGISTRATION_FUNCTION = null;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var InlineAssetsRegistratorChainElementInterface|null
     */
    private $successor;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @inheritDoc
     */
    public function registerAssets(array $assets)
    {
        add_action('wp_enqueue_scripts', function () use ($assets) {
            $event = new InlineAssetsContainingEvent($assets);
            $this->eventDispatcher->dispatch($event, 'moomoo_inline_assets_before_dependencies_registration');
            foreach ($event->getAssets() as $asset) {
                if (!empty($asset->getDependencies())) {
                    if ($asset instanceof ConditionAwareInterface && $asset->hasConditions()) {
                        $evaluated = true;
                        foreach ($asset->getConditions() as $condition) {
                            if ($condition->evaluate() === false) {
                                $evaluated = false;
                                break;
                            }
                        }
                        if (!$evaluated) {
                            continue;
                        }
                        $this->enqueueDependency($asset);
                    } else {
                        $this->enqueueDependency($asset);
                    }
                }
            }
        });
        $assetsByTypes = [];
        foreach (static::ASSETS_TYPES as $possibleAssetType) {
            $assetsByTypes[$possibleAssetType] = [];
        }
        foreach ($assets as $asset) {
            $assetsByTypes[$asset->getType()][] = $asset;
        }
        foreach ($assetsByTypes as $type => $assets) {
            if ($this->isApplicable($type)) {
                $this->registerAssetsByType($type, $assets);
            } elseif ($this->getSuccessor() && $this->getSuccessor()->isApplicable($type)) {
                $this->getSuccessor()->registerAssetsByType($type, $assets);
            }
        }

    }

    /**
     * @param $type
     * @param $assets
     */
    public function registerAssetsByType($type, $assets)
    {
        if ($this->isApplicable($type)) {
            add_action(
                static::ASSET_REGISTRATION_FUNCTION,
                function () use ($type, $assets) {
                    $event = new InlineAssetsContainingEvent($assets);
                    $this->eventDispatcher->dispatch($event, sprintf('moomoo_inline_assets_before_%ss_registration', $type));
                    foreach ($event->getAssets() as $asset) {
                        if ($asset instanceof ConditionAwareInterface && $asset->hasConditions()) {
                            $evaluated = true;
                            foreach ($asset->getConditions() as $condition) {
                                if ($condition->evaluate() === false) {
                                    $evaluated = false;
                                    break;
                                }
                            }
                            if (!$evaluated) {
                                continue;
                            }
                            $this->registerAsset($asset);
                        } else {
                            $this->registerAsset($asset);
                        }
                    }
                },
                30
            );
        } elseif ($this->getSuccessor() && $this->getSuccessor()->isApplicable($type)) {
            $this->registerAssetsByType($type, $assets);
        }
    }

    /**
     * @param array $htmlAttributes
     * @return string
     */
    protected function generateHtmlAttributes(array $htmlAttributes)
    {
        $formattedAttributes = [];
        foreach ($htmlAttributes as $key => $value) {
            $formattedAttributes[] = HtmlAttributesFormatter::format($key, $value);
        }
        if (!empty($formattedAttributes)) {
            return implode(' ', $formattedAttributes);
        }

        return '';
    }

    /**
     * @param InlineAssetsRegistratorChainElementInterface $assetRegistrator
     */
    public function setSuccessor(InlineAssetsRegistratorChainElementInterface $assetRegistrator)
    {
        $this->successor = $assetRegistrator;
    }

    /**
     * @return InlineAssetsRegistratorChainElementInterface|null
     */
    protected function getSuccessor()
    {
        return $this->successor;
    }
}
