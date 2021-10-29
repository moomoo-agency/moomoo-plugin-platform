<?php

namespace MooMoo\Platform\Bundle\TaxonomyBundle\Model;

use MooMoo\Platform\Bundle\KernelBundle\ParameterBag\ParameterBag;

class Term extends ParameterBag implements TermInterface
{
    const NAME_FIELD = 'name';
    const TAXONOMY_FIELD = 'taxonomy';
    const ALIAS_OF_FIELD = 'alias_of';
    const DESCRIPTION_FIELD = 'description';
    const PARENT_FIELD = 'parent';
    const SLUG_FIELD = 'slug';
    const TERM_META_FIELD = 'term_meta';


    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->get(self::NAME_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function setName($name)
    {
        $this->set(self::NAME_FIELD, $name);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTaxonomy()
    {
        return $this->get(self::TAXONOMY_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function setTaxonomy($taxonomy)
    {
        $this->set(self::TAXONOMY_FIELD, $taxonomy);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAliasOf()
    {
        return $this->get(self::ALIAS_OF_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function setAliasOf($aliasOf)
    {
        $this->set(self::ALIAS_OF_FIELD, $aliasOf);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return $this->get(self::DESCRIPTION_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function setDescription($description)
    {
        $this->set(self::DESCRIPTION_FIELD, $description);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getParent()
    {
        return $this->get(self::PARENT_FIELD, 0);
    }

    /**
     * @inheritDoc
     */
    public function setParent($parent)
    {
        $this->set(self::PARENT_FIELD, $parent);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSlug()
    {
        return $this->get(self::SLUG_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function setSlug($slug)
    {
        $this->set(self::SLUG_FIELD, $slug);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTermMeta()
    {
        return $this->get(self::TERM_META_FIELD, []);
    }

    /**
     * @inheritDoc
     */
    public function addTermMeta(TermMetaInterface $termMeta)
    {
        $meta = $this->getTermMeta();
        $meta[] = $termMeta;
        $this->set(self::TERM_META_FIELD, $meta);

        return $this;
    }
}
