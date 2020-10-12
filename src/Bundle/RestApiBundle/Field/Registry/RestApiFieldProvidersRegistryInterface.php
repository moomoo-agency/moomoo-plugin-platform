<?php

namespace MooMoo\Platform\Bundle\RestApiBundle\Field\Registry;

use MooMoo\Platform\Bundle\RestApiBundle\Field\RestApiFieldProviderInterface;

interface RestApiFieldProvidersRegistryInterface
{
    /**
     * @return RestApiFieldProviderInterface[]
     */
    public function getRestApiFieldProviders();
}
