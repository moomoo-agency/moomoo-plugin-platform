<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Model;

interface StyleInterface extends AssetInterface
{
    /**
     * @return string|null
     */
    public function getMedia();
}