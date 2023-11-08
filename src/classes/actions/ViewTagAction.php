<?php


namespace iutnc\touiteur\actions;

use iutnc\touiteur\exception\PostException;


class ViewTagAction extends Action
{

    public function execute(): string
    {
        $id = $_GET['tag'];
        try {
            $tag = Tag::getTag($id);
            $render = new TagRender($tag);
            return $render->render();
        } catch (PostException $e) {
            return "Tag inexistant";
        }
    }
}