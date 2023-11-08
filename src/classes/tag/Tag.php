<?php

namespace iutnc\touiteur\tag;

use Exception;
use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\exception\AuthException;
use iutnc\touiteur\exception\PostException;
use iutnc\touiteur\exception\UserException;
use iutnc\touiteur\user\User;
use iutnc\touiteur\exception\InvalidPropertyNameException;
use PDO;

class Tag
{
    protected int $idTag;
    protected string $libelle;
    protected ?string $description;

    private function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @throws Exception
     */
    public function __set(string $at, mixed $val): void
    {
        if (property_exists($this, $at)) {
            $this->$at = $val;
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

    /**
     * @throws UserException
     * @throws PostException
     */
    public static function getTag(int $id): Tag
    {
        $db = ConnectionFactory::makeConnection();
        $query = "select * from POST where idTag = ?";
        $resultset = $db->prepare($query);
        $resultset->bindParam(1, $id);
        $resultset->execute();

        if (!$resultset) throw new PostException();

        $row = $resultset->fetch(PDO::FETCH_ASSOC);

        if (!$row) throw new PostException();

        $tag = new Tag($id);
        $tag->libelle = $row['libelle'];
        $tag->description = $row['description'];

        return $tag;

    }
}