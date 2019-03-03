<?php

namespace MooMoo\Platform\Bundle\MigrationBundle;

use MooMoo\Platform\Bundle\KernelBundle\Bundle\Bundle;
use MooMoo\Platform\Bundle\KernelBundle\DependencyInjection\CompilerPass\KernelCompilerPass;
use MooMoo\Platform\Bundle\MetaBoxBundle\Registrator\MetaBoxesRegistratorInterface;
use MooMoo\Platform\Bundle\MetaBoxBundle\Registry\MetaBoxesRegistryInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MigrationBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            new KernelCompilerPass(
                'moo_migration',
                'moo_migration.registry.migrations',
                'addMigration'
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        /** @var MetaBoxesRegistryInterface $metaBoxesRegistry */
        $metaBoxesRegistry = $this->container->get('moo_meta_box.registry.meta_boxes');
        /** @var MetaBoxesRegistratorInterface $metaBoxesRegistrator */
        $metaBoxesRegistrator = $this->container->get('moo_meta_box.registrator.meta_boxes');
        $metaBoxesRegistrator->registerMetaBoxes($metaBoxesRegistry->getMetaBoxes());
        
        parent::boot();
    }
}
