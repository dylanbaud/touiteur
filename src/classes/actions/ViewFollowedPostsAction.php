<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\post\PostList;
use iutnc\touiteur\render\PostListRender;
use iutnc\touiteur\user\User;

class ViewFollowedPostsAction extends Action
{

    public function execute(): string
    {
        if(isset($_SESSION['user'])){
            $renderer = new PostListRender(new PostList(User::getFollowedPosts($_SESSION['user']->userId)));
            return $renderer->render();
        }
        else{
            header('Location: ?action=sign-in');
            return '';
        }
    }
}