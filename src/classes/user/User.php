<?php

namespace iutnc\touiteur\user;

use Exception;
use iutnc\touiteur\db\ConnectionFactory;
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
        $row = $resultset->fetch(PDO::FETCH_ASSOC);
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

}
