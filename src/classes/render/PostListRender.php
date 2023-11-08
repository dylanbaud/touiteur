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

        ob_start();
        echo <<<HTML
<div class="post-list">
    <div class="title">
        <img src="/img/logo.png" alt="Logo">
        <h1>Touiteur</h1>
    </div>
HTML;
        $postList = $this->postlist;
        foreach ($postList->posts as $post) {
            $user = $post->user;
            $id = $post->id;
            echo <<<HTML
    <div onclick="location.href='?action=view-post&id=$id'" class="card">
        <a href="?action=view-profile&id={$user->userId}" class="card-profile">
            <img src='$user->profilePic'>
            <p>$user->username</p>
        </a>
        <div class="card-content">
            <p>$post->postText</p>
HTML;
            if ($post->image != null) {
                echo "<img src='$post->image'>";
            }

            echo'</div>
    </div>';
        }
        if (!isset($_GET['id'])) {
            $action = 'default';
            $query = "select count(*) from POST where 1";
            $author = "";

        } else {
            $action = 'view-profile';
            $query = "select count(*) from POST where userId = {$_GET['id']}";
            $author = "&id={$_GET['id']}";
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

        echo <<<HTML
            <div class="pagination">
            HTML;
        if (!isset($_GET['page']))
            $page = 1;
        else {
            $page = $_GET['page'];
        }
        for ($i = 1; $i <= $pageCount; $i++) {
            if ($i = $page) {
                echo <<<HTML
                    <a href="?action={$action}&page={$i}{$author}" id="current-page">{$i}</a>
                    HTML;
                unset($page);
            } else {
                echo <<<HTML
                    <a href="?action={$action}&page={$i}{$author}">{$i}</a>
                    HTML;
            }
        }

        echo '</div>';
        echo '</div>
<div class="right">';

        ob_end_flush();
        return ob_get_clean();
    }
}
