<?php

namespace iutnc\touiteur\render;

use iutnc\touiteur\auth\Auth;
use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\post\Post;
use iutnc\touiteur\post\PostList;
use PDO;

class PostRender
{

    private Post $post;
    private int $page;

    public function __construct(Post $post, int $page)
    {
        $this->post = $post;
        $this->page = $page;
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
                        $outPut .= '<a href="?action=view-tag&tagId='.$tag.'" class="tag">'.$word.'</a> ';
                    }
                }
            } else {
                $outPut .= $word." ";
            }
        }

        $html .= '
            <div class="blur">
                <div class="card better-card">
                    <a href="?action=default&page='.$this->page.'" class="quit-btn"><img src="./img/icon/cancel.png"></a>
                    <a href="?action=view-profile&userId='.$user->userId.'" class="card-profile better-card-profile">
                        <img src='.$user->profilePic.'>
                        <p>'.$user->username.'<span> - '.$user->lastName.' '.$user->firstName.'</span></p>
                    </a>
                    <div class="card-content better-card-content">
                        <p>'.$outPut.'</p>';

        if ($this->post->image != null){
            $html .= '<img src='.$this->post->image.'>';
        }

        $btn1 = '
            <a href="?action=like&id='.$this->post->id.'&like=true" class="upvote-btn">
                <img src="./img/icon/like-empty.png">
            </a>';
        $btn2 = '
            <a href="?action=like&id='.$this->post->id.'&like=false" class="downvote-btn">
                <img src="./img/icon/like-empty.png">
            </a>';

        if (isset($_SESSION['user']) && $_SESSION['user']->hasLiked($this->post->id) == 1){
            $btn1 = '
            <a href="?action=like&id='.$this->post->id.'&like=true" class="upvote-btn">
                <img src="./img/icon/like.png">
            </a>';
        } else if (isset($_SESSION['user']) && $_SESSION['user']->hasLiked($this->post->id) == 0){
            $btn2 = '
            <a href="?action=like&id='.$this->post->id.'&like=false" class="downvote-btn">
                <img src="./img/icon/like.png">
            </a>';
        }

        $html .=
            '<hr>
            <div class="like-btn">
                '.$btn1.'
                <p>'.$this->post->score.'</p>
                '.$btn2.'
            </div>
            <hr>';

        if(Auth::isLogged() && $_SESSION['user']->userId === $user->userId){
            $html .= '
            <a href="?action=delete-post&id='.$this->post->id.'" class="delete-btn">
                <img src="/img/icon/trash.svg">
            </a>';
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