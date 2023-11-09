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

        $html .= <<<HTML
    <div class="tag-profile">
        <div>
            <h2>#{$this->tag->libelle}</h2>
        </div>
HTML;

        if (isset($_SESSION['user'])) {
            $query = "SELECT * FROM LIKEDTAG WHERE idTag = {$id} AND userId = {$_SESSION["user"]->userId}";
            $resultset = $db->prepare($query);
            $resultset->execute();

            if ($resultset->rowCount() === 1) {
                $html .= <<<HTML
        <a href="?action=follow-tag&tagId=$id" id="follow-tag">Se désabonner</a>
HTML;
            } else {
                $html .= <<<HTML
        <a href="?action=follow-tag&tagId=$id" id="follow-tag">S'abonner</a>
HTML;
            }
        }

        return $html;
    }
}
