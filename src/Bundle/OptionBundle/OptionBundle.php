<?php

namespace MooMoo\Platform\Bundle\OptionBundle;

use MooMoo\Platform\Bundle\KernelBundle\Bundle\Bundle;
use MooMoo\Platform\Bundle\KernelBundle\DependencyInjection\CompilerPass\KernelCompilerPass;
use MooMoo\Platform\Bundle\OptionBundle\Registrator\OptionsRegistratorInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OptionBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        /** @var ContainerBuilder $container */
        $container->addCompilerPass(
            new KernelCompilerPass(
                'moomoo_option',
                'moomoo_option.registrator.main',
                'addOption'
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        /** @var OptionsRegistratorInterface $optionsRegistrator */
        $optionsRegistrator = $this->container->get('moomoo_option.registrator.main');
        $optionsRegistrator->registerOptions();

        parent::boot();
    }
}
