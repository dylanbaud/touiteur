<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\actions\Action;
use iutnc\touiteur\auth\Auth;

class SignoutAction extends Action
{

    public function execute(): string
    {
        Auth::logout();
        return <<<HTML
            <div class="default">
                <h2>Vous êtes déconnecté</h2>
            </div>
<div class="right">
HTML;
    }
}