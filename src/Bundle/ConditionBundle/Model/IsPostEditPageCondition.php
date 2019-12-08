<?php

namespace MooMoo\Platform\Bundle\ConditionBundle\Model;

class IsPostEditPageCondition extends AbstractCondition
{
    const NEW_TYPE = 'new';
    const EDIT_TYPE = 'edit';

    const ACTION_FIELD = 'action';
    const POST_TYPE_FIELD = 'post_type';

    /**
     * @var string
     */
    protected $action = null;

    /**
     * @var string
     */
    protected $postType = null;

    /**
     * @var array
     */
    protected $validActions = [
        self::NEW_TYPE,
        self::EDIT_TYPE
    ];

    /**
     * {@inheritdoc}
     */
    public function __construct(array $parameters = [])
    {
        parent::__construct($parameters);

        $arguments = $this->get(self::ARGUMENTS_FIELD, []);

        $action = isset($arguments[self::ACTION_FIELD]) ? $arguments[self::ACTION_FIELD] : null;
        if ($action !== null && !in_array($action, $this->validActions)) {
            throw new \Exception(
                sprintf('Not valid action filled, valid actions are - %s', implode(',', $this->validActions))
            );
        }
        $this->action = $action;

        $postType = isset($arguments[self::POST_TYPE_FIELD]) ? $arguments[self::POST_TYPE_FIELD] : null;
        if ($postType) {
            $this->postType = $postType;
        }
    }

    /**
     * @param $postType
     * @return $this
     */
    public function setPostType($postType)
    {
        $this->postType = $postType;

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function getResult()
    {
        return $this->checkAction() && $this->checkPostType();
    }

    /**
     * @return bool
     */
    private function checkAction()
    {
        global $pagenow;

        if ($this->action === self::EDIT_TYPE) {
            return in_array($pagenow, ['post.php']);
        } elseif ($this->action === self::NEW_TYPE) {
            return in_array($pagenow, ['post-new.php']);
        } else {
            return in_array($pagenow, ['post.php', 'post-new.php']);
        }
    }

    /**
     * @return bool
     */
    private function checkPostType()
    {
        global $typenow;

        if ($this->postType) {
            if ($this->postType === $typenow) {
                return true;
            } else {
                return false;
            }
        }

        return true;
    }
}
