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
                    <img src="/img/logo.png" alt="Logo">
                    <h1>Touiteur</h1>
                </div>
        HTML;
        $postList = $this->postlist;
        foreach ($postList->posts as $post) {
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
            if ($post->image != null) {
                $html .= "<img src='$post->image'>";
            }

            $html .= <<<HTML
            </div>
            </div>
            HTML;
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

        $html = <<<HTML
            <div class="pagination">
            HTML;
        $page = 1;
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        }

        for ($i = 1; $i < $pageCount; $i++) {
            if ($i == $page) {
                $html .= <<<HTML
                    <a href="?action={$action}&page={$page}{$author}" id="current-page">{$page}</a>
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
