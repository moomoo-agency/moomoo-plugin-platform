<?php


namespace MooMoo\Platform\Bundle\HookBundle\Model;

use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareInterface;
use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareTrait;

abstract class AbstractHook implements HookInterface, ConditionAwareInterface
{
    use ConditionAwareTrait;

    /**
     * @var string
     */
    protected $tag;

    /**
     * @var int
     */
    protected $priority;

    /**
     * @var int
     */
    protected $acceptedArgs;

    /**
     * @param string $tag
     * @param int $priority
     * @param int $acceptedArgs
     */
    public function __construct($tag, $priority = 10, $acceptedArgs = 1)
    {
        $this->tag = $tag;
        $this->priority = $priority;
        $this->acceptedArgs = $acceptedArgs;
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @return int
     */
    public function getAcceptedArgs()
    {
        return $this->acceptedArgs;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }
}
