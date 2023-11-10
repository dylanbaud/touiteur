<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\auth\Auth;
use iutnc\touiteur\render\FollowersListRender;

class ViewBackOfficeAction extends Action
{

    public function execute(): string
    {
        if (Auth::isLogged()) {
            $html = '';
            if ($_SESSION['user']->role == 3) {
                $html .= <<<HTML
                <div class="back-office">
                    <a href="?action=view-bestuser">Accéder aux utilisateurs les plus influents</a>
                    <a href="?action=view-besttag">Accéder aux tags les plus mentionnés</a>
                </div>
            </div>
<div class="right"></div>
HTML;
            } else {
                $html .= "Vous n'avez pas accés à cette page";
            }
        } else {
            header('Location: ?action=sign-in');
        }

        return $html;
    }
}