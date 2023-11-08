<?php

namespace iutnc\touiteur\post;

use Exception;
use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\exception\AuthException;
use iutnc\touiteur\exception\PostException;
use iutnc\touiteur\exception\UserException;
use iutnc\touiteur\user\User;
use iutnc\touiteur\exception\InvalidPropertyNameException;
use PDO;

class Post
{
    protected int $id;
    protected string $postText;
    protected ?string $image;
    protected int $score;
    protected string $postDate;
    protected User $user;
    protected ?array $tags;

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
    public static function getPost(int $id): Post
    {
        $db = ConnectionFactory::makeConnection();
        $query = "select * from POST where postId = ?";
        $resultset = $db->prepare($query);
        $resultset->bindParam(1, $id);
        $resultset->execute();

        if (!$resultset) throw new PostException();

        $row = $resultset->fetch(PDO::FETCH_ASSOC);

        if (!$row) throw new PostException();

        $post = new Post($id);
        $post->postText = $row['postText'];
        $post->image = $row['image'];
        $post->score = $row['score'];
        $post->postDate = $row['postDate'];
        $post->user = User::getUser($row['userId']);

        $query = "select idTag from HASTAG where postId = ?";
        $resultset = $db->prepare($query);
        $resultset->bindParam(1, $id);
        $resultset->execute();
        $tags = array();
        while ($row = $resultset->fetch(PDO::FETCH_ASSOC)) {
            $tags[] = $row['idTag'];
        }
        $post->tags = $tags;

        return $post;

    }
}