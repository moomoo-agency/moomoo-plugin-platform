<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Registrator\FooterScripts;

use MooMoo\Platform\Bundle\AssetBundle\Model\FooterScriptInterface;
use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareInterface;

class FooterScriptsRegistrator implements FooterScriptsRegistratorInterface
{
    /**
     * @inheritDoc
     */
    public function registerScripts(array $scripts)
    {
        add_action('wp_enqueue_scripts', function () use ($scripts) {
            foreach ($scripts as $script) {
                if (!empty($script->getDependencies())) {
                    if ($script instanceof ConditionAwareInterface && $script->hasConditions()) {
                        $evaluated = true;
                        foreach ($script->getConditions() as $condition) {
                            if ($condition->evaluate() === false) {
                                $evaluated = false;
                                break;
                            }
                        }
                        if (!$evaluated) {
                            continue;
                        }
                        $this->enqueueDependency($script);
                    } else {
                        $this->enqueueDependency($script);
                    }
                }
            }
        });
        add_action('wp_footer', function () use ($scripts) {
            /** @var FooterScriptInterface[] $scripts */
            foreach ($scripts as $script) {
                if ($script instanceof ConditionAwareInterface && $script->hasConditions()) {
                    $evaluated = true;
                    foreach ($script->getConditions() as $condition) {
                        if ($condition->evaluate() === false) {
                            $evaluated = false;
                            break;
                        }
                    }
                    if (!$evaluated) {
                        continue;
                    }
                    $this->registerScript($script);
                } else {
                    $this->registerScript($script);
                }
            }
        });
    }

    /**
     * @param FooterScriptInterface $script
     */
    private function enqueueDependency(FooterScriptInterface $script)
    {
        if (!empty($script->getDependencies())) {
            $wp_scripts = wp_scripts();
            foreach ($script->getDependencies() as $dependency) {
                if (in_array($dependency, array_keys($wp_scripts->registered))) {
                    $wp_scripts->enqueue($dependency);
                }
            }
        }
    }

    /**
     * @param FooterScriptInterface $script
     */
    private function registerScript(FooterScriptInterface $script)
    {
        echo $this->getFinalContent(
            $script->getId(),
            $script->getType(),
            $script->getContent()
        );
    }

    /**
     * @param string $id
     * @param string $type
     * @param string $content
     * @return string
     */
    private function getFinalContent($id, $type, $content)
    {
        return sprintf(
            '<script%s%s>%s</script>',
            $type ? sprintf(' type="%s"', $type) : '',
            $id ? sprintf(' id="%s"', $id) : '',
            $content
        );
    }
}
