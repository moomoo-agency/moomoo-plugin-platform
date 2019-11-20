<?php

namespace MooMoo\Platform\Bundle\TaxonomyBundle\Model;

use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareInterface;
use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareTrait;

abstract class AbstractTaxonomy implements TaxonomyInterface, ConditionAwareInterface
{
    use ConditionAwareTrait;
    
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
