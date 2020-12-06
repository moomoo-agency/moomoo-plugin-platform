<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Model;

interface FooterScriptInterface
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type);

    /**
     * @return string
     */
    public function getContent();

    /**
     * @param string $content
     * @return $this
     */
    public function setContent($content);

    /**
     * @return string|null
     */
    public function getId();

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id);

    /**
     * @return array
     */
    public function getDependencies();

    /**
     * @param array $dependencies
     * @return $this
     */
    public function setDependencies(array $dependencies);
}
