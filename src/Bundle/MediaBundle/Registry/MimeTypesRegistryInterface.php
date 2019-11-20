<?php

namespace MooMoo\Platform\Bundle\MediaBundle\Registry;

use MooMoo\Platform\Bundle\MediaBundle\Model\MimeTypeInterface;

interface MimeTypesRegistryInterface
{
    /**
     * @return MimeTypeInterface[]
     */
    public function getMimeTypes();

    /**
     * @param string $extension
     * @return MimeTypeInterface|null
     */
    public function getMimeType($extension);

    /**
     * @param string $extension
     * @return bool
     */
    public function hasMimeType($extension);
}
