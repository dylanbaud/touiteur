<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\actions\Action;
use iutnc\touiteur\exception\PostException;
use iutnc\touiteur\exception\UserException;
use iutnc\touiteur\post\Post;
use iutnc\touiteur\render\PostRender;

class ViewPostAction extends Action
{
    protected int $section;

    public function execute(): string
    {
        $id = $_GET['id'];
        $page = $_GET['page'] ?? 1;
        try {
            $post = Post::getPost($id);
            $render = new PostRender($post, $page);
            return $render->render();
        } catch (PostException $e) {
            return "Post inexistant";
        } catch (UserException $e) {
            return "User inexistant";
        }
    }
}