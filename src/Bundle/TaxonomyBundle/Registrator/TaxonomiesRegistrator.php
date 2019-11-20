<?php

namespace MooMoo\Platform\Bundle\TaxonomyBundle\Registrator;

use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareInterface;
use MooMoo\Platform\Bundle\TaxonomyBundle\Model\TaxonomyInterface;
use MooMoo\Platform\Bundle\TaxonomyBundle\Model\Term;

class TaxonomiesRegistrator implements TaxonomiesRegistratorInterface
{
    /**
     * @inheritDoc
     */
    public function registerTaxonomies(array $taxonomies)
    {
        add_action('init', function () use ($taxonomies) {
            /** @var TaxonomyInterface $taxonomy */
            foreach ($taxonomies as $taxonomy) {
                if ($taxonomy instanceof ConditionAwareInterface && $taxonomy->hasConditions()) {
                    $evaluated = true;
                    foreach ($taxonomy->getConditions() as $condition) {
                        if ($condition->evaluate() === false) {
                            $evaluated = false;
                            break;
                        }
                    }
                    if (!$evaluated) {
                        continue;
                    }
                    $this->registerTaxonomy($taxonomy);
                } else {
                    $this->registerTaxonomy($taxonomy);
                }
            }
        });
    }

    /**
     * @param TaxonomyInterface $taxonomy
     */
    private function registerTaxonomy(TaxonomyInterface $taxonomy)
    {
        if (!taxonomy_exists($taxonomy->getName())) {
            register_taxonomy($taxonomy->getName(), $taxonomy->getObjectType(), $taxonomy->getArguments());
            if (!empty($taxonomy->getTerms())) {
                foreach ($taxonomy->getTerms() as $term) {
                    wp_insert_term(
                        $term->getName(),
                        $term->getTaxonomy(),
                        [
                            Term::ALIAS_OF_FIELD => $term->getAliasOf(),
                            Term::DESCRIPTION_FIELD => $term->getDescription(),
                            Term::PARENT_FIELD => $term->getParent(),
                            Term::SLUG_FIELD => $term->getSlug(),
                        ]
                    );
                }
            }
        }
    }
}
