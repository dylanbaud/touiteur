<?php

namespace iutnc\touiteur\render;

use iutnc\touiteur\db\ConnectionFactory;
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
    <div onclick="location.href='?action=view-post&id=$id'" class="card">
        <a href="?action=view-profile&id={$user->userId}" class="card-profile">
            <img src='$user->profilePic'>
            <p>$user->username</p>
        </a>
        <div class="card-content">
            <p>$post->postText</p>
HTML;
            if ($post->image != null){
                $html .= "<img src='$post->image'>";
            }

            $html .= '</div>
    </div>';
        }
        if(!isset($_GET['id'])){
            $query = "select * from POST";
            $db = ConnectionFactory::makeConnection();
            $resultset = $db->prepare($query);
            $resultset->execute();
            $count = $resultset->rowCount();

            $pageCount = ceil($count / 10);

            $html .= <<<HTML
            <div class="pagination">
            HTML;
            for ($i = 1; $i <= $pageCount; $i++){
                if($i = $_GET['page'])
                    $html .= '<a href="?action=default&page='.$i.'" id="current-page">'.$i.'</a>';
                else
                    $html .= '<a href="?action=default&page='.$i.'">'.$i.'</a>';
            }
        } else {
            $query = "select * from POST where userId = ?";
            $db = ConnectionFactory::makeConnection();
            $resultset = $db->prepare($query);
            $resultset->bindParam(1, $_GET['id']);
            $resultset->execute();
            $count = $resultset->rowCount();

            $pageCount = ceil($count / 10);

            $html .= <<<HTML
            <div class="pagination">
            HTML;
            for ($i = 1; $i <= $pageCount; $i++){
                if($i = $_GET['page'])
                    $html .= '<a href="?action=view-profile&page='.$i.'&id='.$_GET['id'].'" id="current-page">'.$i.'</a>';
                else
                    $html .= '<a href="?action=view-profile&page='.$i.'&id='.$_GET['id'].'">'.$i.'</a>';
            }
        }


        $html .= '</div>';
        $html .= '</div>
<div class="right">';
        return $html;
    }
}
