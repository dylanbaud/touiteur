<?php

namespace iutnc\touiteur\post;

use Exception;
use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\exception\PostException;
use iutnc\touiteur\exception\UserException;
use iutnc\touiteur\user\User;
use PDO;

class PostList
{
    private array $posts;

    private function __construct(array $posts = array())
    {
        $this->posts = $posts;
    }

    /**
     * @throws UserException
     * @throws PostException
     */
    public static function getAllPosts(int $min): PostList
    {
        $postList = array();

        $db = ConnectionFactory::makeConnection();
        $query = "select * from POST order by postDate limit 10 offset ?";
        $resultset = $db->prepare($query);
        $resultset->bindParam(1, $min);
        $resultset->execute();

        while ($row = $resultset->fetch(PDO::FETCH_ASSOC)) {
            $post = Post::getPost($row['postId']);
            $postList[] = $post;
        }

        return new PostList($postList);
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
