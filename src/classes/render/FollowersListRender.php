<?php

namespace iutnc\touiteur\render;

use iutnc\touiteur\db\ConnectionFactory;

class FollowersListRender
{
    public function render(): string
    {
        $html = <<<HTML
        <div class="follower-list">
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
            <div class="follower">
            <a href="?action=view-profile&userId={$row['userId']}">
                <img src="{$row['profilePic']}" alt="Profile picture">
                <h2>{$row['username']}</h2>
            </a>
            </div>
            HTML;
            }
        } else {
            $html .= <<<HTML
            <h2>Aucun Utilisateur renseigné</h2>
HTML;

        }
        $html .= <<<HTML
        </div>
        HTML;


        return $html;
    }

}