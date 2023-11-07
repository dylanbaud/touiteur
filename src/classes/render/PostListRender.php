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
        $html = <<<HTML
<div class="post-list">
    <div class="title">
        <h1>Touiteur</h1>
    </div>
HTML;
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
HTML;
            if ($post->image != null){
                $html .= "<img src='$post->image'>";
            }

            $html .= '</div>
    </div>';
        }
        $html .= '
    </div>';
        return $html;
    }


}
