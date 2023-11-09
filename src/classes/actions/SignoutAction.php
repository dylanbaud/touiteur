<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\actions\Action;
use iutnc\touiteur\auth\Auth;

class SignoutAction extends Action
{

    public function execute(): string
    {
        Auth::logout();
        header("Location: ?action=sign-in");
        return "";
    }
}