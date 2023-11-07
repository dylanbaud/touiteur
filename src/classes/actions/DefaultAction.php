<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\actions\Action;
use iutnc\touiteur\post\PostList;

class DefaultAction extends Action
{

    public function execute(): string
    {
        $postList = PostList::getAllPosts(0);
        $postListRender = new \iutnc\touiteur\render\PostListRender($postList);
        return $postListRender->render();
    }
}