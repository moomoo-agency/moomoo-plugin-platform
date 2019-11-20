<?php

namespace MooMoo\Platform\Bundle\AssetBundle\Registrator\FooterTemplateScripts;

use MooMoo\Platform\Bundle\AssetBundle\Model\FooterTemplateScriptInterface;
use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareInterface;
use Symfony\Component\Templating\EngineInterface;

class FooterTemplateScriptsRegistrator implements FooterTemplateScriptsRegistratorInterface
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @param EngineInterface $templating
     */
    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    /**
     * @inheritDoc
     */
    public function registerScripts(array $scripts)
    {
        add_action('wp_footer', function () use ($scripts) {
            /** @var FooterTemplateScriptInterface[] $scripts */
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
     * @param FooterTemplateScriptInterface $script
     */
    private function registerScript(FooterTemplateScriptInterface $script)
    {
        echo $this->getFinalContent(
            $script->getId(),
            $script->getType(),
            $this->templating->render($script->getTemplatePath())
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
        return sprintf('<script type="%s" id="%s">%s</script>', $type, $id, $content);
    }
}
