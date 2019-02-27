<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Model;

use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareInterface;
use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareTrait;

class FooterTemplateScript implements FooterTemplateScriptInterface, ConditionAwareInterface
{
    use ConditionAwareTrait;
    
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $templatePath;
    
    /**
     * @var string
     */
    private $prefix;

    /**
     * @var string
     */
    private $suffix;

    /**
     * @param string $type
     * @param string $templatePath
     * @param string|null $prefix
     * @param string|null $suffix
     */
    public function __construct($type, $templatePath, $prefix = null, $suffix = null)
    {
        $this->type = $type;
        $this->templatePath = $templatePath;
        $this->prefix = $prefix;
        $this->suffix = $suffix;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplatePath()
    {
        return $this->templatePath;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getPrefix()
    {
        return $this->prefix;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getSuffix()
    {
        return $this->suffix;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        $name = str_replace('/', '-', explode('.', explode(':', $this->templatePath)[1])[0]);
        if ($prefix = $this->prefix) {
            $id = sprintf(
                '%s-%s',
                $prefix,
                $name
            );
        } else {
            $id = $name;
        }
        if ($suffix = $this->suffix) {
            $id = sprintf(
                '%s-%s',
                $id,
                $suffix
            );
        }

        return $id;
    }
}
