<?php

namespace MooMoo\Platform\Bundle\KernelBundle\Provider;

class PluginNameForClassProvider
{
    private $bundles;

    /**
     * @param array $bundles
     */
    public function __construct(array $bundles)
    {
        $this->bundles = $bundles;
    }

    /**
     * @param string $class
     * @return string
     */
    public function getPluginName($class)
    {
       foreach ($this->bundles as $name => $bundle) {
            if (strpos($class, $bundle->getNamespace()) !== false) {
                return $bundle->getPluginName();
            }
        }

       return null;
    }
}