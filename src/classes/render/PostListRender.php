<?php

namespace iutnc\touiteur\render;

use iutnc\touiteur\post\PostList;

class PostListRender{

    public PostList $postlist;

    public function __construct(PostList $postList)
    {
        $this->postlist = $postList;
    }

    public function render(): string
    {
        $html = '';

        $postList = $this->postlist;
        foreach ($postList->posts as $post){
            $user = $post->user;
            $html.= $user->email;
            $html .= $post->postText;
            $html .= "<img src='$user->profilePic'>";
        }

        return $html;
    }


}
