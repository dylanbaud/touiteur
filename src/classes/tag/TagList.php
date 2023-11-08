<?php

namespace iutnc\touiteur\tag;

use Exception;
use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\exception\PostException;
use iutnc\touiteur\exception\TagException;
use iutnc\touiteur\exception\UserException;
use iutnc\touiteur\post\Post;
use iutnc\touiteur\user\User;
use PDO;

class TagList
{
    private array $tags;

    public function __construct(array $tags = array())
    {
        $this->tags = $tags;
    }

    /**
     * @throws TagException
     */
    public static function getAllTags(): TagList
    {
        $tagList = array();

        $db = ConnectionFactory::makeConnection();
        $query = "SELECT TAG.idtag as id, TAG.libelle, TAG.description
FROM TAG
LEFT JOIN LIKEDTAG ON TAG.idtag = LIKEDTAG.idtag
GROUP BY TAG.idtag, TAG.libelle, TAG.description
ORDER BY COUNT(LIKEDTAG.idtag) DESC limit 5";
        $resultset = $db->prepare($query);
        $resultset->execute();

        while ($row = $resultset->fetch(PDO::FETCH_ASSOC)) {
            $tag = TAG::getTag($row['id']);
            $tagList[] = $tag;
        }

        return new TagList($tagList);
    }

    /**
     * @throws Exception
     */
    public function __get(string $at): mixed
    {
        if (property_exists($this, $at)) return $this->$at;
        throw new Exception ("$at: invalid property");
    }
}
