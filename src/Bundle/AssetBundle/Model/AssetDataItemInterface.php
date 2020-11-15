<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Model;

interface AssetDataItemInterface
{
    /**
     * @return string
     */
    public function getKey();

    /**
     * @return mixed
     */
    public function getValue();
}