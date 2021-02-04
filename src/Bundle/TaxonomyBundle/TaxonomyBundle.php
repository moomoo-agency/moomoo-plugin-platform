<?php

namespace MooMoo\Platform\Bundle\TaxonomyBundle;

use MooMoo\Platform\Bundle\KernelBundle\Bundle\Bundle;
use MooMoo\Platform\Bundle\KernelBundle\DependencyInjection\CompilerPass\KernelCompilerPass;
use MooMoo\Platform\Bundle\TaxonomyBundle\Registrator\TaxonomiesRegistratorInterface;
use MooMoo\Platform\Bundle\TaxonomyBundle\Registry\TaxonomiesRegistryInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TaxonomyBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            new KernelCompilerPass(
                'moomoo_taxonomy',
                'moomoo_taxonomy.registry.taxonomies',
                'addTaxonomy'
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        /** @var TaxonomiesRegistryInterface $restMetaFieldProvidersRegistry */
        $taxonomiesRegistry = $this->container->get('moomoo_taxonomy.registry.taxonomies');
        /** @var TaxonomiesRegistratorInterface $taxonomiesRegistrator */
        $taxonomiesRegistrator = $this->container->get('moomoo_taxonomy.registrator.taxonomies');
        $taxonomiesRegistrator->registerTaxonomies($taxonomiesRegistry->getTaxonomies());
        
        parent::boot();
    }
}
