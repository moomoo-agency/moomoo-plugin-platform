<?php

namespace MooMoo\Platform\Bundle\MenuBundle\Registrator;

use MooMoo\Platform\Bundle\ConditionBundle\Model\ConditionAwareInterface;
use MooMoo\Platform\Bundle\MenuBundle\Model\AdminBarNodeInterface;
use MooMoo\Platform\Bundle\MenuBundle\Model\MenuElementInterface;

class AdminBarNodesRegistrator implements MenuElementsRegistratorInterface
{
    /**
     * @param AdminBarNodeInterface[]|MenuElementInterface[] $menuElements
     * {@inheritDoc}
     */
    public function register(array $menuElements)
    {
        add_action(
            'init',
            function () use ($menuElements) {
                foreach ($menuElements as $menuElement) {
                    if (!$menuElement instanceof AdminBarNodeInterface) {
                        throw new \Exception('AdminBarNodesRegistrator can register just AdminBarNodeInterface');
                    }
                    if ($menuElement instanceof ConditionAwareInterface && $menuElement->hasConditions()) {
                        $evaluated = true;
                        foreach ($menuElement->getConditions() as $condition) {
                            if ($condition->evaluate() === false) {
                                $evaluated = false;
                                break;
                            }
                        }
                        if (!$evaluated) {
                            return;
                        }
                        $this->addNode($menuElement);
                    } else {
                        $this->addNode($menuElement);
                    }
                }
            }
        );
    }

    /**
     * @param AdminBarNodeInterface $menuElement
     */
    private function addNode(AdminBarNodeInterface $menuElement)
    {
        add_action(
            'admin_bar_menu',
            function (\WP_Admin_Bar $wp_admin_bar) use ($menuElement) {
                $wp_admin_bar->add_node(
                    [
                        'parent' => $menuElement->getParent(),
                        'id' => $menuElement->getIdentifier(),
                        'title' => $menuElement->getTitle(),
                        'href' => $menuElement->getHref(),
                        'meta' => $menuElement->getMeta(),
                        'group' => $menuElement->isGroup()
                    ]
                );
            },
            1000
        );
    }
}
