<?php

namespace iutnc\touiteur\render;

use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\exception\PostException;
use iutnc\touiteur\exception\TagException;
use iutnc\touiteur\exception\UserException;
use iutnc\touiteur\tag\Tag;
use iutnc\touiteur\user\User;
use PDO;


class BestUserListRender
{


    public function render(): string
    {
        $html = "";

        $db = ConnectionFactory::makeConnection();
        $query = "SELECT USER.userId, USER.profilePic, USER.username, COUNT(SUB.userId) AS sub_count
FROM USER
LEFT JOIN SUB ON USER.userId = SUB.userId
GROUP BY USER.userId
ORDER BY COUNT(SUB.userId) DESC limit 10";

        $resultset = $db->prepare($query);
        $resultset->execute();

        if (!$resultset) throw new UserException();

        $row = $resultset->fetch(PDO::FETCH_ASSOC);

        if (!$row) throw new UserException();

        $html .= <<<HTML
    <div class="best">
        <div class="best-user">
            <div class="card">
                <a href="?action=view-profile&userId={$row['userId']}" class="card-profile">
                    <img src='{$row['profilePic']}'>
                    <p>{$row['username']}</p>
                </a>
                <div class="card-content">
                    <p>Follower(s) : {$row['sub_count']}</p>
                </div>
            </div>
HTML;

        while ($row = $resultset->fetch(PDO::FETCH_ASSOC)) {

            $html .= <<<HTML
            <div class="card">
                <a href="?action=view-profile&userId={$row['userId']}" class="card-profile">
                    <img src='{$row['profilePic']}'>
                    <p>{$row['username']}</p>
                </a>
                <div class="card-content">
                    <p>Follower(s) : {$row['sub_count']}</p>
                </div>
            </div>
HTML;

        }

        $html .= <<<HTML
        </div>
        </div>
<div class="right"></div>
HTML;

        return $html;

    }
}