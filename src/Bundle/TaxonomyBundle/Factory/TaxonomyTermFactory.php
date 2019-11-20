<?php

namespace MooMoo\Platform\Bundle\TaxonomyBundle\Factory;

use MooMoo\Platform\Bundle\TaxonomyBundle\Model\Term;

class TaxonomyTermFactory
{
    public static function createTerm($name, $taxonomy, $description, $parent, $slug, $aliasOf)
    {
        return new Term(
            [
                Term::NAME_FIELD => $name,
                Term::TAXONOMY_FIELD => $taxonomy,
                Term::DESCRIPTION_FIELD => $description,
                Term::PARENT_FIELD => $parent,
                Term::SLUG_FIELD => $slug,
                Term::ALIAS_OF_FIELD =>$aliasOf,
            ]
        );
    }
}
