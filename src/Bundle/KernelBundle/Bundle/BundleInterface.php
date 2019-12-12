<?php

namespace MooMoo\Platform\Bundle\KernelBundle\Bundle;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

interface BundleInterface extends ContainerAwareInterface
{
    /**
     * Boots the Bundle.
     */
    public function boot();

    /**
     * Shutdowns the Bundle.
     */
    public function shutdown();

    /**
     * Builds the bundle.
     *
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container);

    /**
     * Returns the container extension that should be implicitly loaded.
     *
     * @return ExtensionInterface|null The default extension or null if there is none
     */
    public function getContainerExtension();

    /**
     * Returns the bundle name (the class short name).
     *
     * @return string The Bundle name
     */
    public function getName();

    /**
     * Gets the Bundle namespace.
     *
     * @return string The Bundle namespace
     */
    public function getNamespace();

    /**
     * Gets the Bundle directory path.
     *
     * The path should always be returned as a Unix path (with /).
     *
     * @return string The Bundle absolute path
     */
    public function getPath();
    
    /**
     * @return string
     */
    public function getPluginName();
}
