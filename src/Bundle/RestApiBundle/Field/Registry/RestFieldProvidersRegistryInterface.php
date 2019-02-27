<?php

namespace MooMoo\Platform\Bundle\RestApiBundle\Field\Registry;

use MooMoo\Platform\Bundle\RestApiBundle\Field\RestFieldProviderInterface;

interface RestFieldProvidersRegistryInterface
{
    /**
     * @return RestFieldProviderInterface[]
     */
    public function getRestFieldProviders();
}
