<?php

namespace MooMoo\Platform\Bundle\RestApiBundle\Controller\Registrator;

use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareInterface;

class RestApiControllersRegistrator implements RestApiControllersRegistratorInterface
{
    /**
     * @inheritDoc
     */
    public function registerRestControllers(array $restControllers)
    {
        add_action( 'rest_api_init', function () use ($restControllers) {
            foreach ($restControllers as $restController) {
                if ($restController instanceof ConditionAwareInterface && $restController->hasConditions()) {
                    $evaluated = true;
                    foreach ($restController->getConditions() as $condition) {
                        if ($condition->evaluate() === false) {
                            $evaluated = false;
                            break;
                        }
                    }
                    if (!$evaluated) {
                        continue;
                    }
                    $restController->register_routes();
                } else {
                    $restController->register_routes();
                }

            }
        });
    }
}
