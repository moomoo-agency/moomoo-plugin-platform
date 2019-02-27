<?php

namespace MooMoo\Platform\Bundle\PostBundle;

use MooMoo\Platform\Bundle\KernelBundle\Bundle\Bundle;
use MooMoo\Platform\Bundle\KernelBundle\DependencyInjection\CompilerPass\KernelCompilerPass;
use MooMoo\Platform\Bundle\PostBundle\PostType\Registrator\PostTypesRegistratorInterface;
use MooMoo\Platform\Bundle\PostBundle\PostType\Registry\PostTypesRegistryInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PostBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            new KernelCompilerPass(
                'moo_post_type',
                'moo_post.registry.post_types',
                'addPostType'
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        /** @var PostTypesRegistryInterface $postTypesRegistry */
        $postTypesRegistry = $this->container->get('moo_post.registry.post_types');
        /** @var PostTypesRegistratorInterface $postTypesRegistrator */
        $postTypesRegistrator = $this->container->get('moo_post.registrator.post_types');
        $postTypesRegistrator->registerPostTypes($postTypesRegistry->getPostTypes());
        
        parent::boot();
    }
}
