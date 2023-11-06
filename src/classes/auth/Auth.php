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

        if (password_verify($passwd, $user['passwd'])) {
            $_SESSION['user'] = $user;
        } else {
            throw new AuthException();
        }
    }

    public static function register(string $email, string $passwd)
    {
        $db = ConnectionFactory::makeConnection();
        $query = 'select count(*) as nb from user where email = ?';
        $resultset = $db->prepare($query);
        $resultset->bindParam(1, $email);
        $resultset->execute();

        if (!$resultset) throw new AuthException();

        $user = $resultset->fetch(PDO::FETCH_ASSOC);

        if ($user['nb'] == 0 && strlen($passwd) >= 10) {

            $hash = password_hash($passwd, PASSWORD_DEFAULT, ['cost' => 12]);
            $query = 'select max(id)+1 from user as id';
            $resultset = $db->prepare($query);
            $resultset->execute();
            $row = $resultset->fetch(PDO::FETCH_ASSOC);

            $query = 'insert into user values (?, ?, "./img/defaultProfile.png", ?, time())';
            $resultset = $db->prepare($query);
            $resultset->bindParam(1, $row['id']);
            $resultset->bindParam(2, $email);
            $resultset->bindParam(3, $hash);
            $resultset->execute();
        } else {
            throw new AuthException();
        }
    }
}