<?php

namespace MooMoo\Platform\Bundle\PostBundle;

use MooMoo\Platform\Bundle\KernelBundle\Bundle\Bundle;
use MooMoo\Platform\Bundle\KernelBundle\DependencyInjection\CompilerPass\KernelCompilerPass;
use MooMoo\Platform\Bundle\PostBundle\PostStatus\Registrator\PostStatusesRegistratorInterface;
use MooMoo\Platform\Bundle\PostBundle\PostStatus\Registry\PostStatusesRegistryInterface;
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
                'moomoo_post_type',
                'moomoo_post.registry.post_types',
                'addPostType'
            )
        );
        $container->addCompilerPass(
            new KernelCompilerPass(
                'moomoo_post_statuses',
                'moomoo_post.registry.post_statuses',
                'addPostStatus'
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        /** @var PostTypesRegistryInterface $postTypesRegistry */
        $postTypesRegistry = $this->container->get('moomoo_post.registry.post_types');
        /** @var PostTypesRegistratorInterface $postTypesRegistrator */
        $postTypesRegistrator = $this->container->get('moomoo_post.registrator.post_types');
        $postTypesRegistrator->registerPostTypes($postTypesRegistry->getPostTypes());

        /** @var PostStatusesRegistryInterface $postStatusesRegistry */
        $postStatusesRegistry = $this->container->get('moomoo_post.registry.post_statuses');
        /** @var PostStatusesRegistratorInterface $postStatusesRegistrator */
        $postStatusesRegistrator = $this->container->get('moomoo_post.registrator.post_statuses');
        $postStatusesRegistrator->registerPostStatuses($postStatusesRegistry->getPostStatuses());

        parent::boot();
    }
}
