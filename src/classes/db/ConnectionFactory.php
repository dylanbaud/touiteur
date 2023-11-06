<?php

namespace iutnc\touiteur\db;

use PDO;

class ConnectionFactory
{
    private static array $config = [] ;

    public static function setConfig($file): void
    {
        self::$config = parse_ini_file($file);
    }

    public static function makeConnection(): PDO
    {
        $dsn = self::$config['driver'] . ':host=' . self::$config['host'] . ';dbname=' . self::$config['database'];
        $db = new PDO($dsn, self::$config['username'], self::$config['password'], [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_STRINGIFY_FETCHES => false,
        ]);

        return $db;
    }

}


