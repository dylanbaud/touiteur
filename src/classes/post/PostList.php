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

    public function isEmpty(): bool{
        return count($this->posts) === 0;
    }

    /**
     * @throws UserException
     * @throws PostException
     */
    public static function getAllPosts(int $min, $s = 1): PostList
    {
        $postList = array();

        $db = ConnectionFactory::makeConnection();

        if(!isset($_SESSION['user']) and isset($_GET['action']) and $_GET['action'] == 'view-following') {
            $query = '';
            header('Location: ?action=sign-in');
        }
        if(isset($_GET['section']) and $_GET['section'] == '2') $query = "SELECT DISTINCT POST.postId as idp FROM POST INNER JOIN SUB ON POST.userId = SUB.userId LEFT JOIN HASTAG ON POST.postId = HASTAG.postId LEFT JOIN LIKEDTAG ON HASTAG.idTag = LIKEDTAG.idTag WHERE SUB.followerId = {$_SESSION['user']->userId} OR LIKEDTAG.userId = {$_SESSION['user']->userId} LIMIT 10 offset $min";
        if(isset($_GET['section']) and $_GET['section'] == '3') return User::getPostListUser($_GET['userId']);
        elseif(!isset($_GET['action']) or !($_GET['action'] == 'view-following')) $query = "select postId as idp from POST order by postDate desc limit 10 offset $min";
        elseif(isset($_SESSION['user']) and $_GET['action'] == 'view-following') $query = "SELECT DISTINCT POST.postId as idp FROM POST INNER JOIN SUB ON POST.userId = SUB.userId LEFT JOIN HASTAG ON POST.postId = HASTAG.postId LEFT JOIN LIKEDTAG ON HASTAG.idTag = LIKEDTAG.idTag WHERE SUB.followerId = {$_SESSION['user']->userId} OR LIKEDTAG.userId = {$_SESSION['user']->userId} ORDER BY postDate DESC LIMIT 10 offset $min";

        $resultset = $db->prepare($query);
        $resultset->execute();

        while ($row = $resultset->fetch(PDO::FETCH_ASSOC)) {
            $post = Post::getPost($row['idp'], $s);
            $postList[] = $post;
        }

        return new PostList($postList);
    }

    public function changeSection(int $n){
        foreach($this->posts as $post){
            $post->setSection($n);
        }
    }

    /**
     * @throws Exception
     */
    public function __get(string $at): mixed
    {
        if (property_exists($this, $at)) return $this->$at;
        throw new Exception ("$at: invalid property");
    }

    public function getSection(){
        return $this->posts[0]->section;
    }
}
