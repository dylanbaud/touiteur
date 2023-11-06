<?php

namespace iutnc\touiteur\auth;

use iutnc\touiteur\AuthException;
use iutnc\touiteur\db\ConnectionFactory;
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

    public static function register(string $username, string $firstname, string $lastname, string $email, string $passwd)
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

        if ($user['nbemail'] == 0 && $user2['nbusername'] == 0 && strlen($passwd) >= 10) {

            $hash = password_hash($passwd, PASSWORD_DEFAULT, ['cost' => 12]);
            $query = 'select max(userid)+1 from USER as id';
            $resultset = $db->prepare($query);
            $resultset->execute();
            $row = $resultset->fetch(PDO::FETCH_ASSOC);

            $query = "insert into USER values (?, ?, ? , './img/defaultProfile.png' , ?, null, null, 1, ?, ?)";
            $resultset = $db->prepare($query);
            $resultset->bindParam(1, $row['id']);
            $resultset->bindParam(2, $email);
            $resultset->bindParam(3, $username);
            $resultset->bindParam(4, $hash);
            $resultset->bindParam(5, $firstname);
            $resultset->bindParam(6, $lastname);
            $resultset->execute();
        } else {
            throw new AuthException();
        }
    }
}