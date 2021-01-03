<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Event;

use MooMoo\Platform\Bundle\AssetBundle\Model\InlineAssetInterface;
use Symfony\Contracts\EventDispatcher\Event;

class InlineAssetsContainingEvent extends Event
{
    /**
     * @var InlineAssetInterface[]
     */
    private $assets;

    /**
     * @param InlineAssetInterface[] $assets
     */
    public function __construct(array $assets)
    {
        $this->assets = $assets;
    }

    /**
     * @return InlineAssetInterface[]
     */
    public function getAssets()
    {
        return $this->assets;
    }

    /**
     * @param InlineAssetInterface[] $assets
     * @return $this
     */
    public function setAssets(array $assets)
    {
        $this->assets = $assets;

        return $this;
    }
}