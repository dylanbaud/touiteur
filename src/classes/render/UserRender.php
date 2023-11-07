<?php

namespace iutnc\touiteur\render;

use iutnc\touiteur\user\User;

class UserRender{

    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function render(): string
    {

        $html = '';

        return $html;
    }


}
