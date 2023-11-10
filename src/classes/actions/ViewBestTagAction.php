<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\auth\Auth;
use iutnc\touiteur\render\BestTagListRender;

class ViewBestTagAction extends Action
{

    public function execute(): string
    {
        if (Auth::isLogged()) {
            $html = '';
            if ($_SESSION['user']->role == 3) {
                $render = new BestTagListRender();
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