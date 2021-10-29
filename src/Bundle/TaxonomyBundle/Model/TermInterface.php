<?php

namespace MooMoo\Platform\Bundle\TaxonomyBundle\Model;

interface TermInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getTaxonomy();

    /**
     * @param string $taxonomy
     * @return $this
     */
    public function setTaxonomy($taxonomy);

    /**
     * @return string
     */
    public function getAliasOf();

    /**
     * @param string $aliasOf
     * @return $this
     */
    public function setAliasOf($aliasOf);

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description);

    /**
     * @return string
     */
    public function getParent();

    /**
     * @param string $parent
     * @return $this
     */
    public function setParent($parent);

    /**
     * @return string
     */
    public function getSlug();

    /**
     * @param string $slug
     * @return $this
     */
    public function setSlug($slug);

    /**
     * @return TermMetaInterface[]
     */
    public function getTermMeta();

    /**
     * @param TermMetaInterface $termMeta
     * @return $this
     */
    public function addTermMeta(TermMetaInterface $termMeta);
}
