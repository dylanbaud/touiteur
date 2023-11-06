<?php

require_once 'vendor/autoload.php';

use iutnc\touiteur\dispatch;

if (isset($_GET['action'])) {
    $dispatcher = new \iutnc\touiteur\dispatch\Dispatcher($_GET['action']);
    $dispatcher->run();
} else {
    $dispatcher = new \iutnc\touiteur\dispatch\Dispatcher('');
    $dispatcher->run();
}