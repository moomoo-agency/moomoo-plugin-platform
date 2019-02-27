<?php

namespace MooMoo\Platform\Bundle\RestApiBundle\Field\Registrator;

use MooMoo\Platform\Bundle\RestApiBundle\Field\RestFieldProviderInterface;

class RestFieldsRegistrator implements RestFieldsRegistratorInterface
{
    /**
     * @inheritDoc
     */
    public function registerFields(array $fieldProviders)
    {
        add_action('rest_api_init', function () use ($fieldProviders) {
            foreach ($fieldProviders as $provider) {
                $this->registerRestField($provider);
            }
        });
    }

    /**
     * @param RestFieldProviderInterface $provider
     */
    private function registerRestField(RestFieldProviderInterface $provider)
    {
        register_rest_field(
            $provider->getObjectType(),
            $provider->getAttribute(),
            [
                'get_callback' => [$provider, 'getGetCallback'],
                'update_callback' =>  [$provider, 'getUpdateCallback'],
                'schema' => [$provider, 'getSchema']
            ]
        );
    }
}
