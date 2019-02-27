<?php

namespace MooMoo\Platform\Bundle\KernelBundle\Kernel;

use MooMoo\Platform\Bundle\KernelBundle\Bundle\BundleInterface;
use Symfony\Component\DependencyInjection\Compiler\MergeExtensionConfigurationPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;

class Kernel
{
    /**
     * @var string
     */
    protected $pluginPrefix = 'moo_plugin';

    /**
     * @var string
     */
    protected $rootDir;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var BundleInterface[]
     */
    protected $bundles = array();

    /**
     * @var bool
     */
    protected $booted = false;

    /**
     * @param string $pluginPrefix
     * @return $this
     */
    public function setPluginPrefix($pluginPrefix)
    {
        $this->pluginPrefix = $pluginPrefix;

        return $this;
    }

    /**
     * @return string
     */
    public function getPluginPrefix()
    {
        return $this->pluginPrefix;
    }

    /**
     * {@inheritdoc}
     */
    public function getRootDir()
    {
        if (null === $this->rootDir) {
            $r = new \ReflectionObject($this);
            $this->rootDir = realpath(dirname($r->getFileName(), 3) . '/../..');
        }

        return $this->rootDir;
    }

    public function registerBundles()
    {
        foreach ($this->collectBundles() as $class => $params) {
            /** @var BundleInterface $bundle */
            $bundle = new $class;
            $this->bundles[$bundle->getName()] = $bundle;
        }
    }

    /**
     * @return array
     */
    public function collectBundles()
    {
        $roots = apply_filters(sprintf('%s_add_root_dir', $this->pluginPrefix), [$this->getRootDir()]);
        $files = $this->findBundles($roots);

        $bundles = array();
        foreach ($files as $file) {
            $import  = Yaml::parse(file_get_contents($file), Yaml::PARSE_CONSTANT);
            if (!empty($import)) {
                if (!empty($import['bundles'])) {
                    $bundles = array_merge($bundles, $this->getBundlesMapping($import['bundles']));
                }
            }
        }
        uasort($bundles, array($this, 'compareBundles'));

        return $bundles;
    }

    /**
     * @param array $a
     * @param array $b
     *
     * @return int
     */
    public function compareBundles($a, $b)
    {
        $p1 = (int)$a['priority'];
        $p2 = (int)$b['priority'];
        if ($p1 === $p2) {
            // bundles with the same priority are sorted alphabetically
            return strcasecmp((string)$a['name'], (string)$b['name']);
        }

        // sort be priority
        return ($p1 < $p2) ? -1 : 1;
    }
    
    /**
     * Finds all .../Resource/config/bundles.yml in given root folders
     *
     * @param array $roots
     *
     * @return array
     */
    protected function findBundles($roots = array())
    {
        $paths = array();
        foreach ($roots as $root) {
            if (!is_dir($root)) {
                continue;
            }
            $root = realpath($root);
            $dir = new \RecursiveDirectoryIterator(
                $root,
                \FilesystemIterator::FOLLOW_SYMLINKS | \FilesystemIterator::SKIP_DOTS
            );
            $filter = new \RecursiveCallbackFilterIterator(
                $dir,
                function (\SplFileInfo $current) use (&$paths) {
                    if (!$current->getRealPath()) {
                        return false;
                    }
                    $fileName = strtolower($current->getFilename());
                    if ($fileName === 'tests' || $current->isFile()) {
                        return false;
                    }
                    if (!is_dir($current->getPathname() . '/Resources')) {
                        return true;
                    } else {
                        $file = $current->getPathname() . '/Resources/config/bundles.yml';
                        if (is_file($file)) {
                            $paths[] = $file;
                        }

                        return false;
                    }
                }
            );

            $iterator = new \RecursiveIteratorIterator($filter);
            $iterator->rewind();
        }

        return $paths;
    }

    /**
     * @param $bundles
     *
     * @return array
     */
    protected function getBundlesMapping(array $bundles)
    {
        $result = array();
        foreach ($bundles as $bundle) {
            $priority = 0;

            if (is_array($bundle)) {
                $class    = $bundle['name'];
                $priority = isset($bundle['priority']) ? (int)$bundle['priority'] : 0;
            } else {
                $class = $bundle;
            }

            $result[$class] = array(
                'name'     => $class,
                'priority' => $priority,
            );
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getBundles()
    {
        return $this->bundles;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getBundle($name)
    {
        if (!isset($this->bundles[$name])) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Bundle "%s" does not exist or it is not enabled.
                     Maybe you forgot to add it in the registerBundles() method of your %s.php file?',
                    $name,
                    get_class($this)
                )
            );
        }

        return $this->bundles[$name];
    }
    
    /**
     * Gets a new ContainerBuilder instance used to build the service container.
     *
     * @return ContainerBuilder
     */
    protected function getContainerBuilder()
    {
        return new ContainerBuilder();
    }

    /**
     * @return ContainerBuilder
     */
    protected function buildContainer()
    {
        $container = $this->getContainerBuilder();
        
        $extensions = array();
        foreach ($this->bundles as $bundle) {
            if ($extension = $bundle->getContainerExtension()) {
                $container->registerExtension($extension);
                $extension->load([], $container);
                //$extensions[] = $extension->getAlias();
            }
        }
        foreach ($this->bundles as $bundle) {
            $bundle->build($container);
        }

        // ensure these extensions are implicitly loaded
        $container->getCompilerPassConfig()->setMergePass(new MergeExtensionConfigurationPass($extensions));
        
        return $container;
    }


    /**
     * Initializes the service container.
     *
     * The cached version of the service container is used when fresh, otherwise the
     * container is built.
     */
    protected function initializeContainer()
    {
        $this->container = $this->buildContainer();
        $this->container->set('kernel', $this);
        $this->container->setParameter('kernel.bundles', $this->getBundles());
        $this->container->compile();
    }
    
    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }


    /**
     * Boots the current kernel.
     */
    public function boot()
    {
        if (true === $this->booted) {
            return;
        }

        // register bundles
        $this->registerBundles();

        // init container
        $this->initializeContainer();

        foreach ($this->getBundles() as $bundle) {
            $bundle->setContainer($this->container);
            $bundle->boot();
        }

        $this->booted = true;
    }
}
