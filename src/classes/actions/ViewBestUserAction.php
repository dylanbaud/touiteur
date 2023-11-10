<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\auth\Auth;
use iutnc\touiteur\render\BestTagListRender;
use iutnc\touiteur\render\BestUserListRender;

class ViewBestUserAction extends Action
{

    public function execute(): string
    {
        if (Auth::isLogged()) {
            $html = '';
            if ($_SESSION['user']->role == 3) {
                $render = new BestUserListRender();
                $html .= $render->render();
            } else {
                $html .= "Vous n'avez pas accés à cette page";
            }
        } else {
            header('Location: ?action=sign-in');
        }

        return $html;
    }
}