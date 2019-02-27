<?php

namespace MooMoo\Platform\Bundle\ConditionBundle\Model;

class IsRequestHasParameterCondition extends AbstractCondition
{
    /**
     * @var string
     */
    protected $requestType;

    /**
     * @var string
     */
    protected $parameter;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var array
     */
    protected $requestTypes = [];

    /**
     * @param string $requestType
     * @param string $parameter
     * @param mixed $value
     */
    public function __construct($requestType, $parameter, $value = null)
    {
        $this->requestType = $requestType;
        $this->parameter = $parameter;
        $this->value = $value;

        $this->requestTypes = [
            'GET' => $_GET,
            'POST' => $_POST
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getResult()
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        if ($this->requestType !== $request_method) {
            return false;
        }
        
        foreach ($this->requestTypes as $requestType => $request) {
            if ($requestType === $request_method && isset($request[$this->parameter])) {
                if ($this->value === null) {
                    return true;
                } else {
                    if ($request[$this->parameter] === $this->value) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        }
        
        return false;
    }
}
