<?php

namespace iutnc\touiteur\render;

use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\exception\PostException;
use iutnc\touiteur\exception\TagException;
use iutnc\touiteur\exception\UserException;
use iutnc\touiteur\tag\Tag;
use iutnc\touiteur\user\User;
use PDO;


class BestTagListRender
{

    public function render(): string
    {
        $html = "";

        $db = ConnectionFactory::makeConnection();
        $query = "SELECT TAG.libelle as libelle, COUNT(HASTAG.idtag) AS tag_count
FROM TAG
LEFT JOIN HASTAG ON TAG.idtag = HASTAG.idtag
GROUP BY libelle
ORDER BY COUNT(HASTAG.idtag) DESC";

        $resultset = $db->prepare($query);
        $resultset->execute();

        if (!$resultset) throw new TagException();

        $row = $resultset->fetch(PDO::FETCH_ASSOC);

        if (!$row) throw new TagException();

        $html .= <<<HTML
    <div class="best">
        <div class="best-tag">
            <div class="card">
                <a href="?action=" class="card-profile">
                    <p>#{$row['libelle']}</p>
                </a>
                <div class="card-content">
                    <p>Mention(s) : {$row['tag_count']}</p>
                </div>
            </div>
HTML;

        while ($row = $resultset->fetch(PDO::FETCH_ASSOC)) {

            $html .= <<<HTML
            <div class="card">
                <a href="?action=" class="card-profile">
                    <p>#{$row['libelle']}</p>
                </a>
                <div class="card-content">
                    <p>Mention(s) : {$row['tag_count']}</p>
                </div>
            </div>
HTML;
        }

        $html .= <<<HTML
        </div>
        </div>
<div class="right"></div>
HTML;

        return $html;

    }
}