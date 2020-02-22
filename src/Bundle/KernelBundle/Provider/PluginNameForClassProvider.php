<?php

namespace MooMoo\Platform\Bundle\KernelBundle\Provider;

class PluginNameForClassProvider
{
    /**
     * array
     */
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
     * @param bool $full
     * @return string
     */
    public function getPluginName($class, $full = true)
    {
       foreach ($this->bundles as $name => $bundle) {
            if (strpos($class, $bundle->getNamespace()) !== false) {
                if ($full === false) {
                    return explode('/', $bundle->getPluginName())[0];
                }

                return $bundle->getPluginName();
            }
        }

       return null;
    }
}