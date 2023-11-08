<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\actions\Action;
<<<<<<< HEAD
use iutnc\touiteur\exception\PostException;
use iutnc\touiteur\exception\UserException;
=======
use iutnc\touiteur\db\ConnectionFactory;
>>>>>>> 50eedca78275fd70a7ed712d9edb7ec7cdf09cd7
use iutnc\touiteur\post\PostList;
use iutnc\touiteur\render\PostListRender;
use iutnc\touiteur\render\TagListRender;
use iutnc\touiteur\tag\TagList;

class DefaultAction extends Action
{

    public function execute(): string
    {
        if(isset($_GET['page'])){
            $min = ($_GET['page']-1) * 10;
        } else {
            $min = 0;
        }
        $postList = PostList::getAllPosts($min);
        $postListRender = new PostListRender($postList);
<<<<<<< HEAD
        $tagListRender = new TagListRender(TagList::getAllTags());
        return $postListRender->render() . $tagListRender->render();
=======

        return $postListRender->render();

>>>>>>> 50eedca78275fd70a7ed712d9edb7ec7cdf09cd7
    }
}