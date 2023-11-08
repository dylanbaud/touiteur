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
        if (isset($_GET['id'])) {
            User::follow($_GET['id'], $_SESSION['user']->userId);
        }
        return $html;
    }
}