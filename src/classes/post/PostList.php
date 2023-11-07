<?php

namespace iutnc\touiteur\post;

use Exception;
use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\user\User;
use PDO;

class PostList
{
    private array $posts;

    private function __construct(array $posts = array())
    {
        $this->posts = $posts;
    }

    public static function getAllPosts(int $min): PostList
    {
        $postList = array();

        $db = ConnectionFactory::makeConnection();
        $query = "select * from POST order by score desc limit 10 offset ?";
        $resultset = $db->prepare($query);
        $resultset->bindParam(1, $min);
        $resultset->execute();

        while ($row = $resultset->fetch(PDO::FETCH_ASSOC)) {
            $post = new Post($row['postId']);
            $post->postText = $row['postText'];
            $post->image = $row['image'];
            $post->score = $row['score'];
            $post->postDate = $row['postDate'];
            $post->user = User::getUser($row['userId']);
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
