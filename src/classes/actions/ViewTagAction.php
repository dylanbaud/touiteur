<?php


namespace iutnc\touiteur\actions;

use iutnc\touiteur\render\PostListRender;
use iutnc\touiteur\tag\Tag;


class ViewTagAction extends Action
{

    public function execute(): string
    {

        if(!isset($_GET['page'])){
            $_GET['page'] = 1;
        }
        
        $id = $_GET['tag'];
        $render = new PostListRender(Tag::getPostListTag($id));
        return $render->render();
    }
}