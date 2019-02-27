<?php

namespace MooMoo\Platform\Bundle\TaxonomyBundle\Registry;

use MooMoo\Platform\Bundle\TaxonomyBundle\Model\TaxonomyInterface;

interface TaxonomiesRegistryInterface
{
    /**
     * @return TaxonomyInterface[]
     */
    public function getTaxonomies();

    /**
     * @param string $name
     * @return TaxonomyInterface|null
     */
    public function getTaxonomy($name);

    /**
     * @param string $name
     * @return bool
     */
    public function hasTaxonomy($name);
}
