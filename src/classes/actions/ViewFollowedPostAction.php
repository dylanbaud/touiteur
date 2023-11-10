<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\post\PostList;
use iutnc\touiteur\render\PostListRender;

class ViewFollowedPostAction
{
    public function execute(): string
    {
        if(isset($_GET['page'])){
            $min = ($_GET['page']-1) * 10;
        } else {
            $min = 0;
        }
        $_SESSION['posting'] = 0;
        $postList = PostList::getAllPosts($min, 2);
        $postListRender = new PostListRender($postList);
        return $postListRender->render();
    }
}