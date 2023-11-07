<?php

require_once 'vendor/autoload.php';

use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\dispatch\Dispatcher;
use iutnc\touiteur\post\PostList;

session_start();

ConnectionFactory::setConfig('./conf/db.config.ini');

if (isset($_GET['action'])) {
    $dispatcher = new Dispatcher($_GET['action']);
    $dispatcher->run();
} else {
    $dispatcher = new Dispatcher('');
    $dispatcher->run();
}

$postList = PostList::getAllPosts(0);
$postListRender = new \iutnc\touiteur\render\PostListRender($postList);
print $postListRender->render();