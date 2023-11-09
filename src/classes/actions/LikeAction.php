<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\post\Post;
use iutnc\touiteur\user\User;

class LikeAction extends Action
{
    public function execute(): string
    {
        if (!isset($_SESSION['user'])){
            header('Location: ?action=sign-in');
        }
        if (isset($_GET['id'])) {
            if(isset($_GET['like'])){
                $value = $_GET['like'];
                if($value == "true"){
                    $value = 1;
                } else {
                    $value = 0;
                }
                User::like($_SESSION['user']->userId,$_GET['id'], $value);
            }

            header("Location: ?action=view-post&id={$_GET['id']}");
        }
        return "";
    }
}