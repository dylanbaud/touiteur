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
    private string $birthdate;
    private int $role;
    private string $firstName;
    private string $lastName;

    private function __construct(int $userId, string $email, string $username, string $profilePic, string $password, string $joinDate, string $birthdate, int $role, string $firstName, string $lastName)
    {
        $this->userId = $userId;
        $this->email = $email;
        $this->username = $username;
        $this->profilePic = $profilePic;
        $this->password = $password;
        $this->joinDate = $joinDate;
        $this->birthdate = $birthdate;
        $this->role = $role;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
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
        $user = new User($row['userId'],$row['email'],$row['username'],$row['profilePic'],$row['password'],$row['joinDate'],$row['birthDate'],$row['role'],$row['firstName'],$row['lastName']);

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
