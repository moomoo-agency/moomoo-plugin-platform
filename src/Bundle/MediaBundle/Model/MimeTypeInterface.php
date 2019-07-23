<?php

namespace MooMoo\Platform\Bundle\MediaBundle\Model;

interface MimeTypeInterface
{
    /**
     * @return string
     */
    public function getExtension();

    /**
     * @return string
     */
    public function getMimeType();
}
