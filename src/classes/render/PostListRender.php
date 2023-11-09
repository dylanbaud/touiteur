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

        if ($_GET['action'] == '') {
            $html .= <<<HTML
            <a href="?action=" class="current-choice">Pour vous</a>
            <a href="?action=view-following">Abonnements</a>
HTML;
        } else if ($_GET['action'] == 'view-following') {
            $html .= <<<HTML
            <a href="?action=">Pour vous</a>
            <a href="?action=view-following" class="current-choice">Abonnements</a>
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

        $db = ConnectionFactory::makeConnection();
        $resultset = $db->prepare($query);
        $resultset->execute();
        $count = $resultset->fetchColumn();
        $pageCount = ceil($count / 10);

        unset($resultset);
        unset($db);
        unset($query);
        unset($count);
        unset($postList);

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