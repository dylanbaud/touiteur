<?php

namespace iutnc\touiteur\render;

use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\exception\PostException;
use iutnc\touiteur\tag\Tag;

class TagRender
{

    private Tag $tag;

    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    public function render(): string
    {
        $html = '';
        $postList = Tag::getPostListTag($this->tag->idTag);
        $postListRender = new PostListRender($postList);
        $html .= $postListRender->render();

        $db = ConnectionFactory::makeConnection();
        $id = $this->tag->idTag;

        $query = "SELECT * FROM LIKEDTAG WHERE userId = {$_SESSION["user"]->userId}";

        $resultset = $db->prepare($query);
        $resultset->execute();

        $likeNb = $resultset->rowCount();

        $html .= <<<HTML
    <div class="tag-profile">
        <h2>#{$this->tag->libelle}</h2>
HTML;

        if ($this->tag->description != null){
            $html .= <<<HTML
        <div class="tag-infos">
            <h3>{$this->tag->description}</h3>
HTML;
        } else {
            $html .= <<<HTML
        <div class="tag-info">
            <h3>Ce tag n'a pas encore de description</h3>
HTML;
        }

        if (isset($_SESSION['user'])) {
            $query = "SELECT * FROM LIKEDTAG WHERE idTag = {$id} AND userId = {$_SESSION["user"]->userId}";
            $resultset = $db->prepare($query);
            $resultset->execute();

            if ($resultset->rowCount() === 1) {
                $html .= <<<HTML
        <a href="?action=follow-tag&tagId=$id" id="follow-tag">Unfollow</a>
HTML;
            } else {
                $html .= <<<HTML
        <a href="?action=follow-tag&tagId=$id" id="follow-tag">Follow</a>
HTML;
            }

            if ($likeNb < 2) {
                $html .= <<<HTML
        <p>{$likeNb} follower</p>
HTML;
            } else {
                $html .= <<<HTML
        <p>{$likeNb} followers</p>
HTML;

            }
        }

        $html .= <<<HTML
        </div>
    </div>
HTML;


        return $html;
    }
}
