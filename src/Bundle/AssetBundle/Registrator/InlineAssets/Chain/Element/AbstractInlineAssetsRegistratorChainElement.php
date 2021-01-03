<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Registrator\InlineAssets\Chain\Element;

use MooMoo\Platform\Bundle\AssetBundle\Event\InlineAssetsContainingEvent;
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
            $this->eventDispatcher->dispatch($event, 'moomoo_inline_assets_before_registration');
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
        add_action(static::ASSET_REGISTRATION_FUNCTION, function () use ($assets) {
            $event = new InlineAssetsContainingEvent($assets);
            $this->eventDispatcher->dispatch($event, 'moomoo_inline_assets_before_registration');
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
        });
    }

    /**
     * @param InlineAssetInterface $asset
     */
    public function registerAsset(InlineAssetInterface $asset)
    {
        if ($this->isApplicable($asset)) {
            $this->register($asset);
        } elseif ($this->getSuccessor() && $this->getSuccessor()->isApplicable($asset)) {
            $this->getSuccessor()->register($asset);
        }
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
