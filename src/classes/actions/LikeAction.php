<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\user\User;

class LikeAction
{
    public function execute(): string
    {
        $html = '';
        if (!isset($_SESSION['user'])){
            header('Location: ?action=sign-in');
        }
        elseif (isset($_GET['id'])) {
            if(isset($_GET['like'])){
                if($_GET['like'] == 'true'){
                    $value = 1;
                } else {
                    $value = -1;
                }
                User::like($_SESSION['user']->userId,$_GET['id'], $value);
            }

            header("Location: ?action=view-post&postId={$_GET['id']}");
        }
        return $html;
    }
}