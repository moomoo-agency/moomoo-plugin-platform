<?php

namespace MooMoo\Platform\Bundle\MediaBundle\Registry;

use MooMoo\Platform\Bundle\MediaBundle\Model\ImageSize;
use MooMoo\Platform\Bundle\MediaBundle\Model\ImageSizeInterface;

class ImageSizesRegistry implements ImageSizesRegistryInterface
{
    const STANDARD_SIZES = ['thumbnail', 'medium', 'medium_large', 'large'];

    /**
     * @var ImageSizeInterface[]
     */
    private $imageSizes = [];

    /**
     * @inheritDoc
     */
    public function getImageSizes()
    {
        if (empty($this->imageSizes)) {
            $wais = & $GLOBALS['_wp_additional_image_sizes'];
            foreach (get_intermediate_image_sizes() as $_size) {
                if (in_array( $_size, self::STANDARD_SIZES)) {
                    $crop = get_option(sprintf("%s_crop", $_size));
                    $this->imageSizes[$_size] = new ImageSize([
                        ImageSize::NAME_FIELD => $_size,
                        ImageSize::WIDTH_FIELD => (int)get_option(sprintf("%s_size_w", $_size)),
                        ImageSize::HEIGHT_FIELD => (int)get_option(sprintf("%s_size_h", $_size)),
                        ImageSize::CROP_FIELD => is_array($crop) ? $crop : (bool)$crop,
                    ]);
                } elseif (isset($wais[$_size])) {
                    $crop = $wais[ $_size ][ImageSize::CROP_FIELD];
                    $this->imageSizes[$_size] = new ImageSize([
                        ImageSize::NAME_FIELD => $_size,
                        ImageSize::WIDTH_FIELD => (int)$wais[$_size][ImageSize::WIDTH_FIELD],
                        ImageSize::HEIGHT_FIELD => (int)$wais[ $_size ][ImageSize::HEIGHT_FIELD],
                        ImageSize::CROP_FIELD => is_array($crop) ? $crop : (bool)$crop,
                    ]);
                }
            }
        }

        return $this->imageSizes;
    }

    /**
     * @inheritDoc
     */
    public function getImageSize($name)
    {
        if ($this->hasImageSize($name)) {
            return $this->getImageSizes()[$name];
        }
        
        return null;
    }

    /**
     * @inheritDoc
     */
    public function hasImageSize($name)
    {
        $imageSizes = $this->getImageSizes();
        if (isset($imageSizes[$name])) {
            return true;
        }
        
        return false;
    }
}
