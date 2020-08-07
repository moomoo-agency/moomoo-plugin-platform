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
    public function getTaxonomy()
    {
        return $this->get(self::TAXONOMY_FIELD);
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
    public function getDescription()
    {
        return $this->get(self::DESCRIPTION_FIELD);
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
    public function getSlug()
    {
        return $this->get(self::SLUG_FIELD);
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
