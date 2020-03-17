<?php

namespace MooMoo\Platform\Bundle\KernelBundle\Provider;

use MooMoo\Platform\Bundle\KernelBundle\Bundle\BundleInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PluginNameForClassProvider
{
    /**
     * @var BundleInterface[]
     */
    private $bundles;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->bundles = $container->get('kernel')->getBundles();
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