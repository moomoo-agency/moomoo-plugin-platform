<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Registrator\Assets\Chain\Element;

use MooMoo\Platform\Bundle\AssetBundle\Model\AssetInterface;
use MooMoo\Platform\Bundle\AssetBundle\Path\AssetPathProviderInterface;
use MooMoo\Platform\Bundle\AssetBundle\Registrator\Assets\AssetsRegistratorInterface;
use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareInterface;

abstract class AbstractAssetsRegistratorChainElement implements
    AssetsRegistratorInterface,
    AssetsRegistratorChainElementInterface
{
    /**
     * @var AssetPathProviderInterface
     */
    protected $pathProvider;

    /**
     * @var string
     */
    protected $registrationFunction = 'wp_enqueue_scripts';

    /**
     * @var AssetsRegistratorChainElementInterface|null
     */
    private $successor;

    /**
     * @param AssetPathProviderInterface $pathProvider
     */
    public function __construct(AssetPathProviderInterface $pathProvider)
    {
        $this->pathProvider = $pathProvider;
    }

    /**
     * @param string $registrationFunction
     */
    public function setRegistrationFunction($registrationFunction)
    {
        $this->registrationFunction = $registrationFunction;
    }

    /**
     * @inheritDoc
     */
    public function registerAssets(array $assets)
    {
        add_action($this->registrationFunction, function () use ($assets) {
            foreach ($assets as $asset) {
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
     * @param AssetInterface $asset
     */
    public function registerAsset(AssetInterface $asset)
    {
        if ($this->isApplicable($asset)) {
            $this->register($asset);
        } elseif ($this->getSuccessor() && $this->getSuccessor()->isApplicable($asset)) {
            $this->getSuccessor()->register($asset);
        }
    }

    /**
     * @param AssetsRegistratorChainElementInterface $assetRegistrator
     */
    public function setSuccessor(AssetsRegistratorChainElementInterface $assetRegistrator)
    {
        $this->successor = $assetRegistrator;
    }

    /**
     * @return AssetsRegistratorChainElementInterface|null
     */
    protected function getSuccessor()
    {
        return $this->successor;
    }
}
