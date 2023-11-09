<?php


namespace iutnc\touiteur\actions;

use iutnc\touiteur\render\TagRender;
use iutnc\touiteur\tag\Tag;


class ViewTagAction extends Action
{

    public function execute(): string
    {

        if(!isset($_GET['page'])){
            $_GET['page'] = 1;
        }

        $id = $_GET['tagId'];
        $render = new TagRender(Tag::getTag($id));
        return $render->render();
    }
}