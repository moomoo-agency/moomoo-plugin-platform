<?php

namespace MooMoo\Platform\Bundle\TaxonomyBundle\Model;

interface TermInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getTaxonomy();

    /**
     * @return string
     */
    public function getAliasOf();

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @return string
     */
    public function getParent();

    /**
     * @return string
     */
    public function getSlug();
}
