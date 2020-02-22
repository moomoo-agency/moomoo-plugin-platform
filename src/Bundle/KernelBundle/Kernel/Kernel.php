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
    protected $pluginBaseName;

    /**
     * @var string
     */
    protected $pluginName;

    /**
     * @var array
     */
    protected $rootDirs = [];

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var BundleInterface[]
     */
    protected $bundles = [];

    /**
     * @var array
     */
    protected $plugins = [];

    /**
     * @var bool
     */
    protected $booted = false;

    public function __construct()
    {
        $pluginRootFile =debug_backtrace(2, 3)[0]['file'];
        $this->pluginBaseName = plugin_basename($pluginRootFile);
        $this->pluginName = explode(DIRECTORY_SEPARATOR, $this->pluginBaseName)[0];
        $this->rootDirs[$this->pluginBaseName][] = realpath(sprintf('%s%ssrc', dirname($pluginRootFile), DIRECTORY_SEPARATOR));

        $r = new \ReflectionObject($this);
        $this->rootDirs[$this->pluginBaseName][] = realpath(dirname($r->getFileName(), 3) . '/../..');
    }

    /**
     * @return string
     */
    public function getPluginName()
    {
        return $this->pluginName;
    }

    /**
     * @return array
     */
    public function getRootDirs()
    {
        return apply_filters(sprintf('%s_add_root_dir', $this->pluginName), $this->rootDirs);
    }

    public function registerBundles()
    {
        foreach ($this->collectBundles() as $class => $params) {
            if (!in_array($params['plugin'], $this->plugins)) {
                $this->plugins[] = $params['plugin'];
            }
            /** @var BundleInterface $bundle */
            $bundle = new $class($params['plugin']);
            $this->bundles[$bundle->getName()] = $bundle;
        }
    }

    /**
     * @return array
     */
    public function collectBundles()
    {
        $files = $this->findBundles($this->getRootDirs());

        $bundles = [];
        foreach ($files as $plugin => $pluginFiles) {
            foreach ($pluginFiles as $file) {
                $import = Yaml::parse(file_get_contents($file), Yaml::PARSE_CONSTANT);
                if (!empty($import)) {
                    if (!empty($import['bundles'])) {
                        $bundles = array_merge($bundles, $this->getBundlesMapping($import['bundles'], $plugin));
                    }
                }
            }
        }
        uasort($bundles, [$this, 'compareBundles']);

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
    protected function findBundles($roots = [])
    {
        $paths = [];
        foreach ($roots as $plugin => $pluginRoots) {
            foreach ($pluginRoots as $root) {
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
                    function (\SplFileInfo $current) use (&$paths, $plugin) {
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
                                $paths[$plugin][] = $file;
                            }

                            return false;
                        }
                    }
                );

                $iterator = new \RecursiveIteratorIterator($filter);
                $iterator->rewind();
            }
        }

        return $paths;
    }

    /**
     * @param array $bundles
     * @param string $plugin
     *
     * @return array
     */
    protected function getBundlesMapping(array $bundles, $plugin)
    {
        $result = [];
        foreach ($bundles as $bundle) {
            $priority = 0;

            if (is_array($bundle)) {
                $class    = $bundle['name'];
                $priority = isset($bundle['priority']) ? (int)$bundle['priority'] : 0;
            } else {
                $class = $bundle;
            }

            $result[$class] = [
                'name'     => $class,
                'priority' => $priority,
                'plugin'   => $plugin
            ];
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
        
        $extensions = [];
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
        $this->container->setParameter('kernel.plugins', $this->plugins);
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
