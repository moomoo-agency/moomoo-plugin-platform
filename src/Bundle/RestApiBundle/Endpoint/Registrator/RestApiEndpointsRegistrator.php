<?php

namespace MooMoo\Platform\Bundle\RestApiBundle\Endpoint\Registrator;

use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareInterface;

class RestApiEndpointsRegistrator implements RestApiEndpointsRegistratorInterface
{
    /**
     * @inheritDoc
     */
    public function registerRestEndpoints(array $endpoints)
    {
        add_action( 'rest_api_init', function () use ($endpoints) {
            foreach ($endpoints as $endpoint) {
                if ($endpoint instanceof ConditionAwareInterface && $endpoint->hasConditions()) {
                    $evaluated = true;
                    foreach ($endpoint->getConditions() as $condition) {
                        if ($condition->evaluate() === false) {
                            $evaluated = false;
                            break;
                        }
                    }
                    if (!$evaluated) {
                        continue;
                    }
                    $endpoint->registerRoutes();
                } else {
                    $endpoint->registerRoutes();
                }
            }
        });
    }
}