<?php

namespace iutnc\touiteur\tag;

use Exception;
use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\exception\AuthException;
use iutnc\touiteur\exception\PostException;
use iutnc\touiteur\exception\TagException;
use iutnc\touiteur\exception\UserException;
use iutnc\touiteur\user\User;
use iutnc\touiteur\exception\InvalidPropertyNameException;
use PDO;

class Tag
{
    protected int $idTag;
    protected string $libelle;
    protected ?string $description;

    private function __construct(int $idTag)
    {
        $this->idTag = $idTag;
    }

    /**
     * @throws Exception
     */
    public function __set(string $at, mixed $val): void
    {
        if (property_exists($this, $at)) {
            $this->$at = $val;
            print $at;
        } else throw new InvalidPropertyNameException();
    }

    /**
     * @throws Exception
     */
    public function __get(string $at): mixed
    {
        if (property_exists($this, $at)) return $this->$at;
        throw new Exception ("$at: invalid property");
    }

    public static function getTag(int $id): Tag
    {
        $db = ConnectionFactory::makeConnection();
        $query = "select * from TAG where idTag = ?";
        $resultset = $db->prepare($query);
        $resultset->bindParam(1, $id);
        $resultset->execute();

        if (!$resultset) throw new TagException();

        $row = $resultset->fetch(PDO::FETCH_ASSOC);

        if (!$row) throw new TagException();

        $tag = new Tag($id);
        $tag->libelle = $row['libelle'];
        $tag->description = $row['description'];

        return $tag;

    }
}