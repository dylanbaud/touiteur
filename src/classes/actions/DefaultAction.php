<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\actions\Action;
use iutnc\touiteur\post\PostList;
use iutnc\touiteur\render\PostListRender;
use iutnc\touiteur\render\TagListRender;
use iutnc\touiteur\tag\TagList;

class DefaultAction extends Action
{

    public function execute(): string
    {
        $postList = PostList::getAllPosts(0);
        $postListRender = new PostListRender($postList);
        
        return $postListRender->render();
    }
}