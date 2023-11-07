<?php

namespace iutnc\touiteur\render;

use iutnc\touiteur\post\Post;

class PostRender
{

    private Post $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function render(): string
    {
        $user = $this->post->user;
        $html = '
<div class="blur">
    <div class="card better-card">
        <a href="?action=" class="quit-btn"><i class="fa-solid fa-xmark"></i></a>
        <div class="card-profile better-card-profile">
            <img src='.$user->profilePic.'>
            <p>'.$user->username.'<span> - '.$user->lastName.' '.$user->firstName.'</span></p>
        </div>
        <div class="card-content better-card-content">
            <p>'.$this->post->postText.'</p>';

        if ($this->post->image != null){
            $html .= '<img src='.$this->post->image.'>';
        }

        $html .= ' 
        </div>
        <footer>
            Posté le '.$this->post->postDate.'
        </footer>
    </div>
</div>';
        return $html;
    }

}