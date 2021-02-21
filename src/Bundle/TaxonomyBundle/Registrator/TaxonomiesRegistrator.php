<?php

namespace MooMoo\Platform\Bundle\TaxonomyBundle\Registrator;

use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareInterface;
use MooMoo\Platform\Bundle\KernelBundle\Cache\RuntimeObjectCache;
use MooMoo\Platform\Bundle\TaxonomyBundle\Model\TaxonomyInterface;
use MooMoo\Platform\Bundle\TaxonomyBundle\Model\Term;

class TaxonomiesRegistrator implements TaxonomiesRegistratorInterface
{
    /**
     * @var RuntimeObjectCache
     */
    private $runtimeObjectCache;

    /**
     * @param RuntimeObjectCache $runtimeObjectCache
     */
    public function __construct(RuntimeObjectCache $runtimeObjectCache)
    {
        $this->runtimeObjectCache = $runtimeObjectCache;
    }

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
        }
        if (!empty($taxonomy->getTerms())) {
            foreach ($taxonomy->getTerms() as $term) {
                $termChecked = $this->runtimeObjectCache->get(
                    sprintf('%s_%s_%s_term_existence_cached', $term->getName(), $term->getTaxonomy(), $term->getParent())
                );
                $termExists = $this->runtimeObjectCache->get(
                    sprintf('%s_%s_%s_term_exists', $term->getName(), $term->getTaxonomy(), $term->getParent())
                );
                if (false === $termChecked) {
                    $termExists = term_exists($term->getName(), $term->getTaxonomy(), $term->getParent());
                    $this->runtimeObjectCache->set(
                        sprintf('%s_%s_%s_term_exists', $term->getName(), $term->getTaxonomy(), $term->getParent()),
                        $termExists
                    );
                    $this->runtimeObjectCache->set(
                        sprintf('%s_%s_%s_term_existence_cached', $term->getName(), $term->getTaxonomy(), $term->getParent()),
                        true
                    );
                }
                if (!$termExists) {
                    $termResult = wp_insert_term(
                        $term->getName(),
                        $term->getTaxonomy(),
                        [
                            Term::ALIAS_OF_FIELD => $term->getAliasOf(),
                            Term::DESCRIPTION_FIELD => $term->getDescription(),
                            Term::PARENT_FIELD => $term->getParent(),
                            Term::SLUG_FIELD => $term->getSlug(),
                        ]
                    );
                    if (!$termResult instanceof \WP_Error && isset($termResult['term_id'])) {
                        $termResult = $termResult['term_id'];
                    }
                } else {
                    $termResult = get_term_by('name', $term->getName(), $term->getTaxonomy());
                    $termResult = $termResult->term_id;
                }
                if (!empty($term->getTermMeta())) {
                    foreach ($term->getTermMeta() as $termMeta) {
                        if (!metadata_exists('term', $termResult, $termMeta->getKey())) {
                            add_term_meta(
                                $termResult,
                                $termMeta->getKey(),
                                $termMeta->getValue(),
                                $termMeta->isUnique()
                            );
                        }
                    }
                }
            }
        }
    }
}
