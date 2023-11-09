<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\exception\UserException;
use iutnc\touiteur\render\UserRender;
use iutnc\touiteur\user\User;

class ViewProfileAction extends Action
{

    public function execute(): string
    {
        if(!isset($_GET['page'])){
            $_GET['page'] = 1;
        }

        if (isset($_GET['userId'])){
            try {
                $user = User::getUser($_GET['userId']);
                $userRender = new UserRender($user);
                return $userRender->render();
            } catch (UserException $e) {
                return 'Problème de connexion';
            }
        } else {
            return 'erreur';
        }
    }
}