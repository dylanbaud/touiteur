<?php

namespace iutnc\touiteur\render;

use iutnc\touiteur\exception\PostException;
use iutnc\touiteur\exception\UserException;
use iutnc\touiteur\user\User;

class UserRender{

    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @throws UserException
     * @throws PostException
     */
    public function render(): string
    {

        $html = '';
        $postList = User::getPostListUser($this->user->userId);
        $postListRender = new PostListRender($postList);
        $html .= $postListRender->render();

        return $html;
    }


}
