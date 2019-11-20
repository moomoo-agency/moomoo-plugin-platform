<?php

namespace MooMoo\Platform\Bundle\MediaBundle\Registry;

use MooMoo\Platform\Bundle\MediaBundle\Model\ImageSizeInterface;

interface ImageSizesRegistryInterface
{
    /**
     * @return ImageSizeInterface[]
     */
    public function getImageSizes();

    /**
     * @param string $name
     * @return ImageSizeInterface|null
     */
    public function getImageSize($name);

    /**
     * @param string $name
     * @return bool
     */
    public function hasImageSize($name);
}
