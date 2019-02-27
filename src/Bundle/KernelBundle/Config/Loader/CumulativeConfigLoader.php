<?php

namespace MooMoo\Platform\Bundle\KernelBundle\Config\Loader;

use MooMoo\Platform\Bundle\KernelBundle\Config\CumulativeResourceInfo;
use MooMoo\Platform\Bundle\KernelBundle\Kernel\Kernel;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CumulativeConfigLoader
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var CumulativeResourceLoaderCollection
     */
    protected $resourceLoaders;

    /**
     * @param string                                              $name The unique name of a configuration resource
     * @param CumulativeResourceLoader|CumulativeResourceLoader[] $resourceLoader
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($name, $resourceLoader)
    {
        if (empty($name)) {
            throw new \InvalidArgumentException('$name must not be empty.');
        }
        if (empty($resourceLoader)) {
            throw new \InvalidArgumentException('$resourceLoader must not be empty.');
        }

        $this->name            = $name;
        $this->resourceLoaders = new CumulativeResourceLoaderCollection(
            is_array($resourceLoader) ? $resourceLoader : [$resourceLoader]
        );
    }

    /**
     * Loads resources
     *
     * @param ContainerInterface $container The container builder
     * @return CumulativeResourceInfo[]
     */
    public function load(ContainerInterface $container)
    {
        $result = [];
        /** @var Kernel $kernel */
        $kernel = $container->get('kernel');
        $bundles    = $kernel->getBundles();
        $appRootDir = $kernel->getRootDir();

        foreach ($bundles as $bundleName => $bundleClass) {
            $reflection   = new \ReflectionClass($bundleClass);
            $bundleDir    = dirname($reflection->getFileName());
            $bundleAppDir = !empty($appRootDir) && is_dir($appRootDir)
                ? $appRootDir . '/Resources/' . $bundleName
                : ''; // this case needs for tests (without app root directory)

            /** @var CumulativeResourceLoader $resourceLoader */
            foreach ($this->resourceLoaders as $resourceLoader) {
                $resource = $resourceLoader->load($bundleClass, $bundleDir, $bundleAppDir);
                if (null !== $resource) {
                    if (is_array($resource)) {
                        foreach ($resource as $res) {
                            $result[] = $res;
                        }
                    } else {
                        $result[] = $resource;
                    }
                }
            }
        }

        return $result;
    }
}
