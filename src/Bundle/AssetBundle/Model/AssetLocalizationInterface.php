<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Model;

interface AssetLocalizationInterface
{
    /**
     * @return string
     */
    public function getObjectName();
    
    /**
     * @return string
     */
    public function getPropertyName();
    
    /**
     * @return mixed
     */
    public function getPropertyData();
}
