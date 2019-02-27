<?php

namespace MooMoo\Platform\Bundle\TaxonomyBundle\Model;

abstract class AbstractTaxonomy implements TaxonomyInterface
{
    /**
     * @var TermInterface[]
     */
    protected $terms = [];

    /**
     * @param TermInterface $term
     * @return $this
     */
    public function addTerm(TermInterface $term)
    {
        $this->terms[$term->getName()] = $term;
        
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getTerms()
    {
        return $this->terms;
    }
}
