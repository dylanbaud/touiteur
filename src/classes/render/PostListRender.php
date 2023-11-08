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
        <img src="/img/logo.png" alt="Logo">
        <h1>Touiteur</h1>
    </div>
HTML;
        $postList = $this->postlist;
        foreach ($postList->posts as $post){
            $user = $post->user;
            $id = $post->id;
            $html .= <<<HTML
    <a href="?action=view-post&id=$id" class="card">
        <div  class="card-profile">
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
    </a>';
        }
        $html .= '</div>
<div class="right">';
        return $html;
    }
}
