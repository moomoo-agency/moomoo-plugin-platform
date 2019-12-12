<?php

namespace MooMoo\Platform\Bundle\ShortcodeBundle;

use MooMoo\Platform\Bundle\KernelBundle\Bundle\Bundle;
use MooMoo\Platform\Bundle\KernelBundle\DependencyInjection\CompilerPass\KernelCompilerPass;
use MooMoo\Platform\Bundle\ShortcodeBundle\Registrator\ShortcodesRegistratorInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ShortcodeBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        /** @var ContainerBuilder $container */
        $container->addCompilerPass(
            new KernelCompilerPass(
                'moomoo_shortcode',
                'moomoo_shortcode.registrator.shortcodes',
                'addShortcode'
            )
        );
    }
    
    /**
     * @inheritDoc
     */
    public function boot()
    {
        /** @var ShortcodesRegistratorInterface $shortcodesRegistrator */
        $shortcodesRegistrator = $this->container->get('moomoo_shortcode.registrator.shortcodes');
        $shortcodesRegistrator->registerShortcodes();

        parent::boot();
    }
}
