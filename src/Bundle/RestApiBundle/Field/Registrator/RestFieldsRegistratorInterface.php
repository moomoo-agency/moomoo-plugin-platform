<?php

namespace MooMoo\Platform\Bundle\RestApiBundle\Field\Registrator;

use MooMoo\Platform\Bundle\RestApiBundle\Field\RestFieldProviderInterface;

interface RestFieldsRegistratorInterface
{
    /**
     * @param RestFieldProviderInterface[] $fieldProviders
     * @throws \InvalidArgumentException
     */
    public function registerFields(array $fieldProviders);
}
