<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Model;

use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareInterface;
use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareTrait;

class Asset implements AssetInterface, ConditionAwareInterface
{
    use ConditionAwareTrait;
    
    /**
     * @var string
     */
    private $handle;

    /**
     * @var string
     */
    private $source;

    /**
     * @var array
     */
    private $dependencies;

    /**
     * @var string
     */
    private $version = false;

    /**
     * @var mixed
     */
    private $extra;

    /**
     * @var string
     */
    private $type;
    
    /**
     * @var string
     */
    private $category;

    /**
     * @var AssetLocalizationInterface[]
     */
    private $localizations = [];

    /**
     * @param string $category
     * @param string $type
     * @param string $handle
     * @param string $source
     * @param array $dependencies
     * @param string|bool $version
     * @param mixed $extra
     */
    public function __construct(
        $category,
        $type,
        $handle,
        $source,
        array $dependencies = [],
        $version = false,
        $extra = null
    ) {
        $this->category = $category;
        $this->type = $type;
        $this->handle = $handle;
        $this->source = $source;
        $this->dependencies = $dependencies;
        $this->version = $version;
        $this->extra = $extra;
    }

    /**
     * @return string
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return mixed
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }
    
    /**
     * @return AssetLocalizationInterface[]
     */
    public function getLocalizations()
    {
        return $this->localizations;
    }
    
    /**
     * @param AssetLocalizationInterface $localization
     * @return $this
     */
    public function addLocalization(AssetLocalizationInterface $localization)
    {
        $this->localizations[] = $localization;
    }
}
