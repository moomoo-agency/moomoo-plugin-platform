<?php

namespace MooMoo\Platform\Bundle\MediaBundle;

use MooMoo\Platform\Bundle\HookBundle\Registrator\HooksRegistratorInterface;
use MooMoo\Platform\Bundle\HookBundle\Registry\HooksRegistryInterface;
use MooMoo\Platform\Bundle\KernelBundle\Bundle\Bundle;
use MooMoo\Platform\Bundle\KernelBundle\DependencyInjection\CompilerPass\KernelCompilerPass;
use MooMoo\Platform\Bundle\MediaBundle\Registrator\ImageSizesRegistratorInterface;
use MooMoo\Platform\Bundle\MediaBundle\Registrator\MimeTypesRegistratorInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MediaBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            new KernelCompilerPass(
                'moomoo_image_size',
                'moomoo_media.registrator.image_sizes',
                'addImageSize'
            )
        );
        $container->addCompilerPass(
            new KernelCompilerPass(
                'moomoo_mime_type',
                'moomoo_media.registrator.mime_types',
                'addMimeType'
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        /** @var ImageSizesRegistratorInterface $imageSizesRegistrator */
        $imageSizesRegistrator = $this->container->get('moomoo_media.registrator.image_sizes');
        $imageSizesRegistrator->registerImageSizes();

        /** @var MimeTypesRegistratorInterface $mimeTypesRegistrator */
        $mimeTypesRegistrator = $this->container->get('moomoo_media.registrator.mime_types');
        $mimeTypesRegistrator->registerMimeTypes();
        
        parent::boot();
    }
}
