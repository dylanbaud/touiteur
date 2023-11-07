<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\actions\Action;
use iutnc\touiteur\post\Post;
use iutnc\touiteur\render\PostRender;

class ViewPostAction extends Action
{

    public function execute(): string
    {
        $id = $_GET['id'];
        $post = Post::getPost($id);
        $render = new PostRender($post);
        return $render->render();
    }
}