<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Model;

use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareInterface;
use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareTrait;
use MooMoo\Platform\Bundle\KernelBundle\ParameterBag\ParameterBag;

class FooterScript extends ParameterBag implements FooterScriptInterface, ConditionAwareInterface
{
    use ConditionAwareTrait;

    const TYPE_FIELD = 'type';
    const ID_FIELD = 'id';
    const CONTENT_FIELD = 'content';
    const DEPENDENCIES_FIELD = 'dependencies';
    /**
     * @inheritDoc
     */
    public function getType()
    {
        return $this->get(self::TYPE_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function setType($type)
    {
        $this->set(self::TYPE_FIELD, $type);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getContent()
    {
        return $this->get(self::CONTENT_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function setContent($content)
    {
        $this->set(self::CONTENT_FIELD, $content);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->get(self::ID_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function setId($id)
    {
        $this->set(self::ID_FIELD, $id);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return $this->get(self::DEPENDENCIES_FIELD, []);
    }

    /**
     * @inheritDoc
     */
    public function setDependencies(array $dependencies)
    {
        $this->set(self::DEPENDENCIES_FIELD, $dependencies);

        return $this;
    }
}
