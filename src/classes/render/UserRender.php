<?php

namespace iutnc\touiteur\render;

use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\exception\PostException;
use iutnc\touiteur\exception\UserException;
use iutnc\touiteur\user\User;

class UserRender
{

    private User $user;
    protected int $section;

    public function __construct(User $user, int $s)
    {
        $this->user = $user;
        $this->section = $s;
    }

    /**
     * @throws UserException
     * @throws PostException
     */
    public function render(): string
    {
        $html = '';
        $postList = User::getPostListUser($this->user->userId, $this->section);
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
        if (isset($_SESSION['user']) and !($_SESSION['user']->userId === $id)) {
            $query = "SELECT * FROM SUB WHERE userId = {$id} AND followerId = {$_SESSION["user"]->userId}";
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
        <a href="?action=show-followers&userid={$this->user->userId}" class="followers">{$followersNb} follower</a>
HTML;
        } else {
            $html .= <<<HTML
        <a href="?action=show-followers&userid={$this->user->userId}" class="followers">{$followersNb} followers</a>
HTML;
        }

        if(isset($_SESSION['user']) && $_SESSION['user']->userId === $this->user->userId) {
            $avgScore = User::getAvgScore($this->user->userId);

            if ($this->user->userId === $_SESSION['user']->userId) {
                $html .= <<<HTML
                <p>Score moyen : {$avgScore}</p>
            HTML;
            }
        }

        $html .= <<<HTML
        <p> Né(e) le {$this->user->birthDate}</p>
        <p> A rejoint Touiteur le {$this->user->joinDate}</p>
    </div>
    HTML;



        if (isset($_SESSION['user']) && $_SESSION['user']->userId === $this->user->userId) {

            $html .= <<<HTML
    <div class="user-options">
    HTML;

            if ($_SESSION['user']->role == 3){
                $html .= <<<HTML
        <a href="?action=view-backoffice">Back Office</a>
HTML;
            }

            $html .= <<<HTML
        <a href="?action=settings">Modifier le profil</a>
        <a href="?action=sign-out" class="deconnexion">Se déconnecter</a>
    </div>
HTML;
        }
        return $html;
    }
}
