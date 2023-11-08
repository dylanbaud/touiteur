<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\exception\UserException;
use iutnc\touiteur\render\UserRender;
use iutnc\touiteur\user\User;

class FollowUserAction extends Action
{
    public function execute(): string
    {
        $html = '';
        if (!isset($_SESSION['user'])){
            header('Location: ?action=sign-in');
        }
        elseif (isset($_GET['id'])) {
            User::follow($_GET['id'], $_SESSION['user']->userId);
            header("Location: ?action=view-profile&id={$_GET['id']}");
        }
        return $html;
    }
}