<?php

namespace MooMoo\Platform\Bundle\KernelBundle\Kernel;

use MooMoo\Platform\Bundle\KernelBundle\Bundle\BundleInterface;
use MooMoo\Platform\Bundle\KernelBundle\Provider\PluginsVersionsProvider;
use Symfony\Bridge\ProxyManager\LazyProxy\PhpDumper\ProxyDumper;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\Compiler\MergeExtensionConfigurationPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Dumper\XmlDumper;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class Kernel
{
    /**
     * @var bool
     */
    protected $debug;

    /**
     * @var array
     */
    private static $freshCache = [];

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

    /**
     * @param false $debug
     */
    public function __construct($debug = false)
    {
        $this->debug = $debug;
        $pluginRootFile =debug_backtrace(2, 3)[0]['file'];
        $this->pluginBaseName = plugin_basename($pluginRootFile);
        $this->pluginName = explode('/', $this->pluginBaseName)[0];
        $this->rootDirs[$this->pluginBaseName][] = realpath(sprintf('%s/src', dirname($pluginRootFile)));

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
     * Gets the container's base class.
     *
     * All names except Container must be fully qualified.
     *
     * @return string
     */
    protected function getContainerBaseClass()
    {
        return 'Container';
    }

    /**
     * Initializes the service container.
     *
     * The cached version of the service container is used when fresh, otherwise the
     * container is built.
     */
    protected function initializeContainer()
    {
        $cacheValidForPluginsVersions = false;
        $prefix = strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', explode('\\', get_class($this)))[0]);
        $fs = new Filesystem();
        $currentPluginsVersions = (new PluginsVersionsProvider($this->plugins))->getPluginsVersions();
        foreach ($currentPluginsVersions as $plugin => $version) {
            if (strpos($plugin, '/') !== false) {
                register_activation_hook(
                    sprintf('%s/%s', wp_normalize_path( WP_PLUGIN_DIR ), $plugin),
                    function () use ($prefix, $fs){
                        $fs->remove(sprintf('%s/%s/cache', wp_upload_dir()['basedir'], $prefix));
                    }
                );
                register_deactivation_hook(
                    sprintf('%s/%s', wp_normalize_path( WP_PLUGIN_DIR ), $plugin),
                    function () use ($prefix, $fs){
                        $fs->remove(sprintf('%s/%s/cache', wp_upload_dir()['basedir'], $prefix));
                    }
                );
            }
        }
        $cachedPluginsVersions = json_decode(get_option($prefix . 'cached-plugin-versions'), true);
        if ($currentPluginsVersions === $cachedPluginsVersions) {
            $cacheValidForPluginsVersions = true;
        }

        $class = 'MooMooCachedContainer';
        $cacheDir = sprintf('%s/%s/cache', wp_upload_dir()['basedir'], $prefix);
        $cache = new ConfigCache($cacheDir.'/'.$class.'.php', $this->debug);
        $cachePath = $cache->getPath();

        // Silence E_WARNING to ignore "include" failures - don't use "@" to prevent silencing fatal errors
        $errorLevel = error_reporting(\E_ALL ^ \E_WARNING);

        try {
            if (file_exists($cachePath) && \is_object($this->container = include $cachePath)
                && (!$this->debug || (self::$freshCache[$cachePath] ?? $cache->isFresh()))
                && $cacheValidForPluginsVersions === true
            ) {
                self::$freshCache[$cachePath] = true;
                $this->container->set('kernel', $this);
                error_reporting($errorLevel);

                return;
            }
        } catch (\Throwable $e) {
        }

        $oldContainer = \is_object($this->container) ? new \ReflectionClass($this->container) : $this->container = null;

        try {
            is_dir($cacheDir) ?: mkdir($cacheDir, 0777, true);

            if ($lock = fopen($cachePath, 'w')) {
                chmod($cachePath, 0666 & ~umask());
                flock($lock, LOCK_EX | LOCK_NB, $wouldBlock);

                if (!flock($lock, $wouldBlock ? LOCK_SH : LOCK_EX)) {
                    fclose($lock);
                } else {
                    $cache = new class($cachePath, $this->debug) extends ConfigCache {
                        public $lock;

                        public function write($content, array $metadata = null)
                        {
                            rewind($this->lock);
                            ftruncate($this->lock, 0);
                            fwrite($this->lock, $content);

                            if (null !== $metadata) {
                                file_put_contents($this->getPath().'.meta', serialize($metadata));
                                @chmod($this->getPath().'.meta', 0666 & ~umask());
                            }

                            if (\function_exists('opcache_invalidate') && filter_var(ini_get('opcache.enable'), FILTER_VALIDATE_BOOLEAN)) {
                                @opcache_invalidate($this->getPath(), true);
                            }
                        }

                        public function __destruct()
                        {
                            flock($this->lock, LOCK_UN);
                            fclose($this->lock);
                        }
                    };
                    $cache->lock = $lock;

                    if (!\is_object($this->container = include $cachePath)) {
                        $this->container = null;
                    } elseif (!$oldContainer || \get_class($this->container) !== $oldContainer->name) {
                        $this->container->set('kernel', $this);

                        return;
                    }
                }
            }
        } catch (\Throwable $e) {
        } finally {
            error_reporting($errorLevel);
        }

        if ($collectDeprecations = $this->debug && !\defined('PHPUNIT_COMPOSER_INSTALL')) {
            $collectedLogs = [];
            $previousHandler = set_error_handler(function ($type, $message, $file, $line) use (&$collectedLogs, &$previousHandler) {
                if (E_USER_DEPRECATED !== $type && E_DEPRECATED !== $type) {
                    return $previousHandler ? $previousHandler($type, $message, $file, $line) : false;
                }

                if (isset($collectedLogs[$message])) {
                    ++$collectedLogs[$message]['count'];

                    return null;
                }

                $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5);
                // Clean the trace by removing first frames added by the error handler itself.
                for ($i = 0; isset($backtrace[$i]); ++$i) {
                    if (isset($backtrace[$i]['file'], $backtrace[$i]['line']) && $backtrace[$i]['line'] === $line && $backtrace[$i]['file'] === $file) {
                        $backtrace = \array_slice($backtrace, 1 + $i);
                        break;
                    }
                }

                $collectedLogs[$message] = [
                    'type' => $type,
                    'message' => $message,
                    'file' => $file,
                    'line' => $line,
                    'trace' => [$backtrace[0]],
                    'count' => 1,
                ];

                return null;
            });
        }
        try {
            $container = null;
            $container = $this->buildContainer();
            $container->set('kernel', $this);
            $container->setParameter('kernel.plugins', $this->plugins);
            $container->compile();
        } finally {
            if ($collectDeprecations) {
                restore_error_handler();

                file_put_contents($cacheDir.'/'.$class.'Deprecations.log', serialize(array_values($collectedLogs)));
                file_put_contents($cacheDir.'/'.$class.'Compiler.log', null !== $container ? implode("\n", $container->getCompiler()->getLog()) : '');
            }
        }

        $this->dumpContainer($cache, $container, $class, $this->getContainerBaseClass());
        unset($cache);
        $this->container = require $cachePath;
        $this->container->set('kernel', $this);

        update_option($prefix . 'cached-plugin-versions', json_encode($currentPluginsVersions));

        if ($oldContainer && \get_class($this->container) !== $oldContainer->name) {
            // Because concurrent requests might still be using them,
            // old container files are not removed immediately,
            // but on a next dump of the container.
            static $legacyContainers = [];
            $oldContainerDir = \dirname($oldContainer->getFileName());
            $legacyContainers[$oldContainerDir.'.legacy'] = true;
            foreach (glob(\dirname($oldContainerDir).\DIRECTORY_SEPARATOR.'*.legacy', GLOB_NOSORT) as $legacyContainer) {
                if (!isset($legacyContainers[$legacyContainer]) && @unlink($legacyContainer)) {
                    (new Filesystem())->remove(substr($legacyContainer, 0, -7));
                }
            }

            touch($oldContainerDir.'.legacy');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function dumpContainer(ConfigCache $cache, ContainerBuilder $container, $class, $baseClass)
    {
        // cache the container
        $dumper = new PhpDumper($container);

        if (class_exists('ProxyManager\Configuration') && class_exists('Symfony\Bridge\ProxyManager\LazyProxy\PhpDumper\ProxyDumper')) {
            $dumper->setProxyDumper(new ProxyDumper());
        }

        $content = $dumper->dump([
            'class' => $class,
            'base_class' => $baseClass,
            'file' => $cache->getPath(),
            'as_files' => true,
            'debug' => $this->debug,
            'build_time' => $container->hasParameter('kernel.container_build_time')
                ? $container->getParameter('kernel.container_build_time')
                : time(),
        ]);

        $rootCode = array_pop($content);
        $dir = \dirname($cache->getPath()).'/';
        $fs = new Filesystem();

        foreach ($content as $file => $code) {
            $fs->dumpFile($dir.$file, $code);
            @chmod($dir.$file, 0666 & ~umask());
        }
        $legacyFile = \dirname($dir.$file).'.legacy';
        if (file_exists($legacyFile)) {
            @unlink($legacyFile);
        }

        $cache->write($rootCode, $container->getResources());

        if ($this->debug === true) {
            $prefix = \strtolower(\preg_replace('/(?<!^)[A-Z]/', '-$0', \explode('\\', \get_class($this)))[0]);
            $cacheDir = \sprintf('%s/%s/cache/', wp_upload_dir()['basedir'], $prefix);
            $class = 'MooMooCachedContainer';

            $xmlCache = new ConfigCache($cacheDir . '/' . $class . '.xml', false);
            $xmlCache->write((new XmlDumper($container))->dump(), $container->getResources());
        }
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
