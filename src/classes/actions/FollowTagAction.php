<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\tag\Tag;

class FollowTagAction extends Action
{
    public function execute(): string
    {
        $html = '';
        if (!isset($_SESSION['user'])){
            header('Location: ?action=sign-in');
        }
        elseif (isset($_GET['tagId'])) {
            Tag::follow($_SESSION['user']->userId, $_GET['tagId']);
            header("Location: ?action=view-tag&tagId={$_GET['tagId']}");
        }
        return $html;
    }
}