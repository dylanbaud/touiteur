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
        $html = '<div class="post-list">';
        $postList = $this->postlist;
        foreach ($postList->posts as $post){
            $user = $post->user;
            $html .= <<<HTML
    <div class="card">
        <div class="card-profile">
            <img src='$user->profilePic'>
            <p>$user->username</p>
        </div>
        <div class="card-content">
            <p>$post->postText</p>
            <img src='$post->image'>
        </div>
    </div>
HTML;
        }
        $html .= '</div>';
        return $html;
    }


}
