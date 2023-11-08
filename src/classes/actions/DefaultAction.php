<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\actions\Action;
use iutnc\touiteur\exception\PostException;
use iutnc\touiteur\exception\UserException;
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
        $tagListRender = new TagListRender(TagList::getAllTags());
        return $postListRender->render() . $tagListRender->render();
    }
}