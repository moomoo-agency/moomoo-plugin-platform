<?php

namespace MooMoo\Platform\Bundle\ConditionBundle\Model;

class IsRequestHasParameterCondition extends AbstractCondition
{
    const REQUEST_TYPE_FIELD = 'requestType';
    const PARAMETER_FIELD = 'parameter';
    const VALUE_FIELD = 'value';

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
     * {@inheritdoc}
     */
    public function __construct(array $parameters = [])
    {
        parent::__construct($parameters);

        $arguments = $this->get(self::ARGUMENTS_FIELD, []);
        $this->requestType = isset($arguments[self::REQUEST_TYPE_FIELD]) ? $arguments[self::REQUEST_TYPE_FIELD] : null;
        $this->parameter = isset($arguments[self::PARAMETER_FIELD]) ? $arguments[self::PARAMETER_FIELD] : null;
        $this->value = isset($arguments[self::VALUE_FIELD]) ? $arguments[self::VALUE_FIELD] : null;

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
