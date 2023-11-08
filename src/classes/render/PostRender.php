<?php

namespace iutnc\touiteur\render;

use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\post\Post;
use iutnc\touiteur\post\PostList;
use PDO;

class PostRender
{

    private Post $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function render(): string
    {
        $postList = new PostListRender(PostList::getAllPosts(0));
        $html = $postList->render();
        $user = $this->post->user;
        $tags = $this->post->tags;
        $words = explode(" ", $this->post->postText);
        $outPut = "";
        foreach ($words as $word){
            if (substr($word, 0, 1) == "#"){
                $temp = substr($word, 1);

                foreach ($tags as $tag){
                    $db = ConnectionFactory::makeConnection();
                    $query = "select libelle from TAG where idTag = ?";
                    $resultset = $db->prepare($query);
                    $resultset->bindParam(1, $tag);
                    $resultset->execute();
                    $row = $resultset->fetch(PDO::FETCH_ASSOC);
                    if($row['libelle'] == $temp){
                        $outPut .= '<a href="?action=view-tag&tag='.$tag.'">'.$word.'</a> ';
                    }
                }
            } else {
                $outPut .= $word." ";
            }
        }

        $html .= '
<div class="blur">
    <div class="card better-card">
        <a href="?action=" class="quit-btn"><img src="./img/cancel.png"></a>
        <a href="?action=view-profile&id='.$user->userId.'" class="card-profile better-card-profile">
            <img src='.$user->profilePic.'>
            <p>'.$user->username.'<span> - '.$user->lastName.' '.$user->firstName.'</span></p>
        </a>
        <div class="card-content better-card-content">
            <p>'.$outPut.'</p>';

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