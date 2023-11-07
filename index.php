<?php

require_once 'vendor/autoload.php';

use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\dispatch\Dispatcher;

session_start();

ConnectionFactory::setConfig('./conf/db.config.ini');

if (isset($_GET['action'])) {
    $dispatcher = new Dispatcher($_GET['action']);
    $dispatcher->run();
} else {
    $dispatcher = new Dispatcher('');
    $dispatcher->run();
}
