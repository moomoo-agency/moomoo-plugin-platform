<?php

namespace MooMoo\Platform\Bundle\TaxonomyBundle\Model;

interface TaxonomyInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string|array
     */
    public function getObjectType();

    /**
     * @return array
     */
    public function getArguments();

    /**
     * @return TermInterface[]
     */
    public function getTerms();
}
