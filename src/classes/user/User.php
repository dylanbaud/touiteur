<?php

namespace iutnc\touiteur\user;

use Exception;
use iutnc\touiteur\exception\AuthException;
use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\exception\PostException;
use iutnc\touiteur\exception\UserException;
use iutnc\touiteur\post\Post;
use iutnc\touiteur\post\PostList;
use PDO;

class User
{

    private int $userId;
    private string $email;
    private string $username;
    private string $profilePic;
    private string $password;
    private string $joinDate;
    private string $birthDate;
    private int $role;
    private string $firstName;
    private string $lastName;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public
    static function getUser(int $id): User
    {
        $db = ConnectionFactory::makeConnection();

        $query = 'select *
from USER
where userId = ?';

        $resultset = $db->prepare($query);
        $resultset->bindParam(1, $id);
        $resultset->execute();

        if (!$resultset) throw new UserException();

        $row = $resultset->fetch(PDO::FETCH_ASSOC);

        if (!$row) throw new UserException();

        $user = new User($row['email']);
        $user->userId = $row['userId'];
        $user->username = $row['username'];
        $user->profilePic = $row['profilePic'];
        $user->password = $row['password'];
        $user->joinDate = $row['joinDate'];
        $user->birthDate = $row['birthDate'];
        $user->role = $row['role'];
        $user->firstName = $row['firstName'];
        $user->lastName = $row['lastName'];
        return $user;
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
    public static function getPostListUser(int $id): PostList
    {
        $postList = array();

        $db = ConnectionFactory::makeConnection();
        $query = "select * from POST where userId = ? order by postDate desc limit 10 offset ?";
        $resultset = $db->prepare($query);
        if (!isset($_GET['page']) || $_GET['page'] < 1) {
            $_GET['page'] = 0;
        } else {
            $offset = ($_GET['page'] - 1) * 10;
        }
        $resultset->bindParam(1, $id);
        $resultset->bindParam(2, $offset);
        $resultset->execute();

        while ($row = $resultset->fetch(PDO::FETCH_ASSOC)) {
            $post = Post::getPost($row['postId']);
            $postList[] = $post;
        }

        return new PostList($postList);
    }

    public static function follow(int $userId, int $idFollow)
    {
        $db = ConnectionFactory::makeConnection();
        $query = "SELECT followerId FROM SUB WHERE userId = '$userId' AND followerId = '$idFollow'";
        $resultset = $db->prepare($query);
        $resultset->execute();
        if ($resultset->rowCount() === 1) {
            $query = "DELETE FROM SUB WHERE userId = '$userId' AND followerId = '$idFollow'";
            $resultset = $db->prepare($query);
            $resultset->execute();
        } else {
            $query = "INSERT INTO SUB VALUES ('$userId', '$idFollow')";
            $resultset = $db->prepare($query);
            $resultset->execute();
        }
    }

    public static function like(int $userId, int $postId, int $value)
    {
        $db = ConnectionFactory::makeConnection();
        $query = "SELECT * FROM HASLIKED WHERE postId = '$postId' AND userId = '$userId'";
        $resultset = $db->prepare($query);
        $resultset->execute();
        $row = $resultset->fetch(PDO::FETCH_ASSOC);

        if ($value == 1) {
            $like = 1;
        } else {
            $like = -1;
        }

        if ($resultset->rowCount() != 0) {

            if ($row['isLike'] != $value) {
                $query = "UPDATE HASLIKED SET isLike = $value WHERE postId = '$postId' AND userId = '$userId'";
                $resultset = $db->prepare($query);
                $resultset->execute();
                $like = $like * 2;
                $query = "UPDATE POST SET score = score + '$like' WHERE postId = '$postId'";
                $resultset = $db->prepare($query);
                $resultset->execute();
            } else {
                $query = "DELETE FROM HASLIKED WHERE postId = '$postId' AND userId = '$userId'";
                $resultset = $db->prepare($query);
                $resultset->execute();
                $query = "UPDATE POST SET score = score - '$like' WHERE postId = '$postId'";
                $resultset = $db->prepare($query);
                $resultset->execute();
            }

        } else {
            $query = "INSERT INTO HASLIKED VALUES ('$userId', '$postId', '$value')";
            $resultset = $db->prepare($query);
            $resultset->execute();
            $query = "UPDATE POST SET score = score + '$like' WHERE postId = '$postId'";
            $resultset = $db->prepare($query);
            $resultset->execute();
        }
        header("Location: ?action=view-post&id=$postId");
    }
}
