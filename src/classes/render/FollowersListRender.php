<?php

namespace iutnc\touiteur\render;

use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\exception\PostException;
use iutnc\touiteur\exception\UserException;
use iutnc\touiteur\user\User;


class FollowersListRender
{

    /**
     * @throws UserException
     * @throws PostException
     */
    public function render(): string
    {
        $userId = $_GET['userid'];
        $user = User::getUser($userId);
        $userRender = new UserRender($user);
        $html = $userRender->render();

        $html .= <<<HTML
        <div class="blur">
            <div class="card better-card">
                <a href="?action=view-profile&userId={$userId}" class="quit-btn"><img src="./img/icon/cancel.png"></a>
                <h2>Followers de {$user->username}</h2>
        HTML;

        $db = ConnectionFactory::makeConnection();


        if(isset($_GET['userid'])){
            $query = "SELECT * FROM SUB WHERE userId = {$_GET['userid']}";
            $resultset = $db->prepare($query);
            $resultset->execute();
            foreach ($resultset as $row) {
                $query = "SELECT * FROM USER WHERE userId = {$row['followerId']}";
                $resultset = $db->prepare($query);
                $resultset->execute();
                $row = $resultset->fetch();
                $html .= <<<HTML
                <a href="?action=view-profile&userId={$row['userId']}" class="card-profile better-card-profile">
                    <img src="{$row['profilePic']}" alt="Profile picture">
                    <p>{$row['username']}</p>
                </a>
                <hr>
            HTML;
            }
        } else {
            $html .= <<<HTML
            <h2>Aucun Utilisateur renseigné</h2>
HTML;

        }
        $html .= <<<HTML
        </div>
        </div>
        HTML;


        return $html;
    }

}