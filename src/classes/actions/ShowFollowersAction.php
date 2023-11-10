<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\render\FollowersListRender;

class ShowFollowersAction extends Action
{

    public function execute(): string
    {
        $followerListRender = new FollowersListRender();
        return $followerListRender->render();
    }
}