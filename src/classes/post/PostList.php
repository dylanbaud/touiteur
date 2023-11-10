<?php

namespace iutnc\touiteur\post;

use Exception;
use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\exception\PostException;
use iutnc\touiteur\exception\UserException;
use iutnc\touiteur\render\PostListRender;
use iutnc\touiteur\user\User;
use PDO;

class PostList
{
    private array $posts;

    public function __construct(array $posts = array())
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

        $query = '';
        if(!isset($_GET['action']) or $_GET['action'] == 'default') $query = "select postId as idp from POST order by postDate desc limit 10 offset $min";
        elseif(isset($_SESSION['user'])) $query = "SELECT DISTINCT POST.postId as idp FROM POST INNER JOIN SUB ON POST.userId = SUB.userId LEFT JOIN HASTAG ON POST.postId = HASTAG.postId LEFT JOIN LIKEDTAG ON HASTAG.idTag = LIKEDTAG.idTag WHERE SUB.followerId = {$_SESSION['user']->userId} OR LIKEDTAG.userId = {$_SESSION['user']->userId} LIMIT 10 offset $min";
        else header('Location: ?action=sign-in');
        $resultset = $db->prepare($query);
        $resultset->execute();

        while ($row = $resultset->fetch(PDO::FETCH_ASSOC)) {
            $post = Post::getPost($row['idp']);
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
