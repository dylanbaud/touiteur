<?php

namespace iutnc\touiteur\auth;

use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\exception\AuthException;
use iutnc\touiteur\user\User;
use PDO;

class Auth
{
    public static function authentificate(string $email, string $passwd)
    {

        $db = ConnectionFactory::makeConnection();
        $query = 'select * from USER where email = ?';
        $resultset = $db->prepare($query);
        $resultset->bindParam(1, $email);
        $resultset->execute();

        if (!$resultset) throw new AuthException();

        $user = $resultset->fetch(PDO::FETCH_ASSOC);

        if (!$user) throw new AuthException();

        if (password_verify($passwd, $user['password'])) {
            $_SESSION['user'] = $user;
        } else {
            throw new AuthException();
        }
    }

    public static function register(string $username, string $firstname, string $lastname, string $email, string $passwd, string $birthday)
    {

        $db = ConnectionFactory::makeConnection();
        $query = 'select count(*) as nbemail from USER where email = ?';
        $resultset = $db->prepare($query);
        $resultset->bindParam(1, $email);
        $resultset->execute();
        if (!$resultset) throw new AuthException();
        $user = $resultset->fetch(PDO::FETCH_ASSOC);

        $query = 'select count(*) as nbusername from USER where username = ?';
        $resultset = $db->prepare($query);
        $resultset->bindParam(1, $username);
        $resultset->execute();
        if (!$resultset) throw new AuthException();
        $user2 = $resultset->fetch(PDO::FETCH_ASSOC);

        if ($user['nbemail'] == 0 && $user2['nbusername'] == 0 && strlen($passwd) >= 8) {

            $hash = password_hash($passwd, PASSWORD_DEFAULT, ['cost' => 12]);

            $query = "insert into USER (username, email, profilepic, password, joinDate, birthDate, role, firstName, lastName) values (?, ? , './img/defaultProfile.png' , ?, ?, ?, 1, ?, ?)";
            $resultset = $db->prepare($query);
            $resultset->bindParam(1, $username);
            $resultset->bindParam(2, $email);
            $resultset->bindParam(3, $hash);
            $date = date('Y-m-d');
            $resultset->bindParam(4, $date);
            $resultset->bindParam(5, $birthday);
            $resultset->bindParam(6, $firstname);
            $resultset->bindParam(7, $lastname);
            $resultset->execute();

            $query = 'select userId from USER where email = ?';
            $resultset = $db->prepare($query);
            $resultset->bindParam(1, $email);
            $resultset->execute();
            if (!$resultset) throw new AuthException();
            $row = $resultset->fetch(PDO::FETCH_ASSOC);

            $_SESSION['user'] = User::getUser($row['userId']);
        } else {
            throw new AuthException();
        }
    }

    public static function isLogged(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function logout() : void
    {
        unset($_SESSION['user']);
    }
}