<?php

namespace MooMoo\Platform\Bundle\KernelBundle\Bundle;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

abstract class Bundle implements BundleInterface
{
    use ContainerAwareTrait;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var ExtensionInterface
     */
    protected $extension;

    /**
     * @var string
     */
    protected $path;
    
    /**
     * @var string
     */
    protected $pluginName;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @param string $pluginName
     */
    public function __construct($pluginName)
    {
        $this->pluginName = $pluginName;
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function shutdown()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $extension = $this->createContainerExtension();
            if (null !== $extension) {
                if (!$extension instanceof ExtensionInterface) {
                    throw new \LogicException(
                        sprintf(
                            'Extension %s must implement 
                            Symfony\Component\DependencyInjection\Extension\ExtensionInterface.',
                            get_class($extension)
                        )
                    );
                }
                // check naming convention
                $basename = preg_replace('/Bundle$/', '', $this->getName());
                $expectedAlias = Container::underscore($basename);
                if ($expectedAlias != $extension->getAlias()) {
                    throw new \LogicException(
                        sprintf(
                            'Users will expect the alias of the default extension of a bundle to be the underscored
                             version of the bundle name ("%s"). You can override "Bundle::getContainerExtension()"
                              if you want to use "%s" or another alias.',
                            $expectedAlias, $extension->getAlias()
                        )
                    );
                }
                $this->extension = $extension;
            } else {
                $this->extension = false;
            }
        }
        if ($this->extension) {
            return $this->extension;
        }
        
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespace()
    {
        if (null === $this->namespace) {
            $this->parseClassName();
        }
        return $this->namespace;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        if (null === $this->path) {
            $reflected = new \ReflectionObject($this);
            $this->path = dirname($reflected->getFileName());
        }
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function getPluginName()
    {
        return $this->pluginName;
    }

    /**
     * {@inheritdoc}
     */
    final public function getName()
    {
        if (null === $this->name) {
            $this->parseClassName();
        }
        return $this->name;
    }

    /**
     * Returns the bundle's container extension class.
     *
     * @return string
     */
    protected function getContainerExtensionClass()
    {
        $basename = preg_replace('/Bundle$/', '', $this->getName());
        return $this->getNamespace().'\\DependencyInjection\\'.$basename.'Extension';
    }
    
    /**
     * Creates the bundle's container extension.
     *
     * @return ExtensionInterface|null
     */
    protected function createContainerExtension()
    {
        if (class_exists($class = $this->getContainerExtensionClass())) {
            return new $class();
        }
        
        return null;
    }
    
    private function parseClassName()
    {
        $pos = strrpos(static::class, '\\');
        $this->namespace = false === $pos ? '' : substr(static::class, 0, $pos);
        if (null === $this->name) {
            $this->name = false === $pos ? static::class : substr(static::class, $pos + 1);
        }
    }
}
