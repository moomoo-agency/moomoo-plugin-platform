<?php

namespace MooMoo\Platform\Bundle\ConditionBundle\Model;

class IsPostEditPage extends AbstractCondition
{
    const NEW_TYPE = 'new';
    const EDIT_TYPE = 'edit';

    /**
     * @var string
     */
    protected $actionType = null;

    /**
     * @var string
     */
    protected $postType = null;

    /**
     * @var array
     */
    protected $validActionTypes = [
        self::NEW_TYPE,
        self::EDIT_TYPE
    ];

    /**
     * @param string|null $actionType
     * @throws \Exception
     */
    public function __construct($actionType = null)
    {
        if ($actionType !== null && !in_array($actionType, $this->validActionTypes)) {
            throw new \Exception(
                sprintf('Not valid actionType filled, valid types are - %s', implode(',', $this->validActionTypes))
            );
        }

        $this->actionType = $actionType;
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
        if (!is_admin()) {
            return false;
        }
        
        return $this->checkAction() && $this->checkPostType();
    }

    /**
     * @return bool
     */
    private function checkAction()
    {
        global $pagenow;

        if ($this->actionType === self::EDIT_TYPE) {
            return in_array($pagenow, ['post.php']);
        } elseif ($this->actionType === self::NEW_TYPE) {
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
