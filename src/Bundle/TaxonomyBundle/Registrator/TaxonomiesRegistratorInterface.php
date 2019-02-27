<?php

namespace MooMoo\Platform\Bundle\TaxonomyBundle\Registrator;

use MooMoo\Platform\Bundle\TaxonomyBundle\Model\TaxonomyInterface;

interface TaxonomiesRegistratorInterface
{
    /**
     * @param TaxonomyInterface[] $taxonomies
     */
    public function registerTaxonomies(array $taxonomies);
}
