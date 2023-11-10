<?php

namespace iutnc\touiteur\render;

use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\post\PostList;

class PostListRender
{

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
        <div>
            <img src="/img/logo.png" alt="Logo">
            <h1>Touiteur</h1>
        </div>
        <div class="post-choice">
HTML;

        if (!isset($_GET['page'])) {
            $page = 1;
        }
        else {
            $page = $_GET['page'];
        }

        if (isset($_GET['action'])) {
            if ($_GET['action'] == '') {
                $html .= <<<HTML
            <a href="?action=default" class="current-choice">Pour vous</a>
            <a href="?action=view-following">Abonnements</a>
HTML;
            } else if ($_GET['action'] == 'view-following') {
                $html .= <<<HTML
            <a href="?action=default">Pour vous</a>
            <a href="?action=view-following" class="current-choice">Abonnements</a>
HTML;
        } else if ($_GET['action'] != 'view-profile') {
            $html .= <<<HTML
            <a href="?action=default" class="current-choice">Pour vous</a>
            <a href="?action=view-following">Abonnements</a>
HTML;
            }
        } else {
            $html .= <<<HTML
            <a href="?action=default" class="current-choice">Pour vous</a>
            <a href="?action=view-following">Abonnements</a>
HTML;
        }
        $html .= <<<HTML
        </div>
    </div>
HTML;
        $postList = $this->postlist;
        foreach ($postList->posts as $post) {
            $user = $post->user;
            $id = $post->id;
            if(!$postList->isEmpty()){
                $html .= <<<HTML
    <div onclick="location.href='?action=view-post&id=$id&page=$page'" class="card">
        <a href="?action=view-profile&userId={$user->userId}" class="card-profile">
            <img src='$user->profilePic'>
            <p>$user->username</p>
        </a>
        <div class="card-content">
            <p>$post->postText</p>
HTML;
                if ($post->image != null) {
                    $html .= "<img src='$post->image'>";
                }
            }else{
                $html .= "<p>Vous ne suivez personne</p>";
            }

            $html .= '</div>
    </div>';
        }
        if (!isset($_GET['userId'])) {
            if(!isset($_GET['tagId'])){
                $action = 'default';
                $query = "select count(*) from POST where 1";
                $author = "";
            } else {
                $action = 'view-tag';
                $query = "select count(*) from HASTAG where idTag = {$_GET['tagId']}";
                $author = "&tagId={$_GET['tagId']}";
            }


        } else {
            $action = 'view-profile';
            $query = "select count(*) from POST where userId = {$_GET['userId']}";
            $author = "&userId={$_GET['userId']}";
        }

        if(isset($_GET['action']) and $_GET['action'] == 'view-following'){
            $action = 'view-following';
            $author = "";
            $query = "SELECT count(*) FROM POST INNER JOIN SUB ON POST.userId = SUB.userId LEFT JOIN HASTAG ON POST.postId = HASTAG.postId LEFT JOIN LIKEDTAG ON HASTAG.idTag = LIKEDTAG.idTag WHERE SUB.followerId = {$_SESSION['user']->userId} OR LIKEDTAG.userId = {$_SESSION['user']->userId}";
        }

        $db = ConnectionFactory::makeConnection();
        $resultset = $db->prepare($query);
        $resultset->execute();
        $count = $resultset->fetchColumn();
        $pageCount = ceil($count / 10);

        $html .= <<<HTML
            <div class="pagination">
            HTML;
        for ($i = 1; $i <= $pageCount; $i++) {
            if ($i == $page) {
                $html .= <<<HTML
                    <a href="?action={$action}&page={$i}{$author}" id="current-page">{$i}</a>
                    HTML;
            } else {
                $html .= <<<HTML
                    <a href="?action={$action}&page={$i}{$author}">{$i}</a>
                    HTML;
            }
        }

        $html .= '</div>';
        $html .= '</div>
<div class="right">';
        return $html;
    }
}