<?php

namespace iutnc\touiteur\render;

use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\exception\PostException;
use iutnc\touiteur\exception\UserException;
use iutnc\touiteur\user\User;

class UserRender
{

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

        $db = ConnectionFactory::makeConnection();
        $id = $this->user->userId;

        $query = "SELECT * FROM SUB WHERE userId = {$id}";

        $resultset = $db->prepare($query);
        $resultset->execute();

        $followersNb = $resultset->rowCount();

        $html .= <<<HTML
    <div class="user-profile">
        <div>
            <img src="{$this->user->profilePic}" alt="Profile picture">
            <h2>{$this->user->username}</h2>
        </div>
HTML;
        if(isset($_SESSION['user']) and !($_SESSION['user']->userId === $this->user->userId)) {
            $query = "SELECT * FROM SUB WHERE userId = '$id' AND followerId = '{$_SESSION['user']->userId}'";
            $resultset = $db->prepare($query);
            $resultset->execute();

            if ($resultset->rowCount() === 1) {
                $html .= <<<HTML
        <a href="?action=follow-user&id=$id" id="follow-user">Unfollow</a>
HTML;
            } else {
                $html .= <<<HTML
        <a href="?action=follow-user&id=$id" id="follow-user">Follow</a>
HTML;
            }
        }
        elseif(!$_SESSION['user']->userId === $this->user->userId){
            $html .= <<<HTML
        <a href="?action=follow-user&id=$id" id="follow-user">Follow</a>
HTML;
            }
        }
        if ($this->user->role === 2) {
            $html .= <<<HTML
            <p>Influenceur</p>
            <p>Contact : {$this->user->email}</p>
HTML;
        }

        $html .= <<<HTML
        <p>{$this->user->lastName} {$this->user->firstName}</p>
HTML;
        if ($followersNb < 2) {
            $html .= <<<HTML
        <p>{$followersNb} follower</p>
HTML;
        } else {
            $html .= <<<HTML
        <p>{$followersNb} followers</p>
HTML;
        }
        $html .= <<<HTML
        <p> Né(e) le {$this->user->birthDate}</p>
        <p> A rejoint Touiteur le {$this->user->joinDate}</p>
    </div>
    HTML;

        if (isset($_SESSION['user']) && $_SESSION['user']->userId === $this->user->userId) {
            $html .= <<<HTML
    <div class="user-options">
        <a href="?action=settings">Modifier le Profil</a>
        <a href="?action=sign-out" class="deconnexion">Se déconnecter</a>
    </div>
HTML;
        }
        return $html;
    }
}
