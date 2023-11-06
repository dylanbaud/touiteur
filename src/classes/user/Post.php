<?php

namespace iutnc\touiteur\user;

use iutnc\touiteur\db\ConnectionFactory;

class Post
{

    private string $id;

    function __construct(string $id) {
        $this->id = $id;
    }

    function getInfos() : array {
        $infos = [];

        $bd = ConnectionFactory::makeConnection();
        $sql = // TODO : SQL request
        $res = $bd->prepare($sql);
        $res->execute();

        while ($row = $res->fetch()) {
            $infos[] = $row;
        }

        return $infos;
    }

}