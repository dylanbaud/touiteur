<?php

namespace iutnc\touiteur\user;

use iutnc\touiteur\db\ConnectionFactory;

class User {

    private string $email;

    function __construct(string $email) {
        $this->email = $email;
    }

    function getPosts() : array {
        $posts = [];

        $bd = ConnectionFactory::makeConnection();
        $sql = // TODO : SQL request
        $res = $bd->prepare($sql);
        $res->execute();

        while ($row = $res->fetch()) {
            $post = new Post($row['id']);
            $posts[] = $post;
        }

        return $posts;
    }

}
