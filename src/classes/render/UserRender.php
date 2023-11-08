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

        $html .= <<<HTML
    <div class="user-profile">
        <div>
            <img src="{$this->user->profilePic}" alt="Profile picture">
            <h2>{$this->user->username}</h2>
        </div>
        <p>{$this->user->lastName} {$this->user->firstName}</p>
        <p>Né(e) le {$this->user->birthDate}</p>
        <p>A rejoint Touiteur le {$this->user->joinDate}</p>
    </div>
    <div class="user-options">
        <a href="?action=settings">Modifier le Profil</a>
        <a href="?action=sign-out" class="deconnexion">Se déconnecter</a>
    </div>
HTML;

        return $html;
    }


}
