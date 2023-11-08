<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\auth\Auth;
use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\post\PostList;
use iutnc\touiteur\render\PostListRender;
use SplFileInfo;

class CreatePostAction extends Action
{

    public function execute(): string
    {
        $db = ConnectionFactory::makeConnection();
        $postList = new PostListRender(PostList::getAllPosts(0));
        $html = $postList->render();

        if ($this->http_method === 'GET' && Auth::isLogged()) {
            $html .= <<<HTML
<div class="blur transparent-blur">
    <div class="poster">
        <a href="?action=" class="quit-btn"><img src="./img/cancel.png"></a>
        <form method="post" action="?action=create-post" enctype='multipart/form-data'>
            <div class="text-content">
                <img src="/img/defaultProfile.png" class="profile-pic">
                <textarea name="text" id="text" placeholder="Quoi de neuf ?" maxlength="235" required></textarea>
            </div>
            
            <hr>
            
            <div class="buttons">
                <label for="inputfile">
                    <img src="./img/image.png">
                </label>
                <input type="file" name="inputfile" id="inputfile">
                <input type="submit" value="Poster" class="submit">
            </div>
        </form>
    </div>
</div>
HTML;

        } elseif ($this->http_method === 'POST' && Auth::isLogged()) {

            $text = filter_var($_POST['text'], FILTER_SANITIZE_STRING);

            if (($_FILES['inputfile']['error'] === UPLOAD_ERR_OK) && explode('/', $_FILES['inputfile']['type'])[0] == 'image' && ($_FILES['inputfile']['size'] < 20000000)) {
                $upload_dir = 'img/post/';
                $filename = uniqid() . "." . explode('/', $_FILES['inputfile']['type'])[1];
                $tmp = $_FILES['inputfile']['tmp_name'];
                $dest = $upload_dir . $filename;
                if (!move_uploaded_file($tmp, $dest)) {
                    print "hum, hum téléchargement non valide<br>";
                }
            }
            $query = 'insert into POST (postText, image, postDate, score, userId) VALUES (?, ? , NOW(), 0, ?)';
            $resultset = $db->prepare($query);
            $resultset->bindParam(1, $text);
            $resultset->bindParam(2, $dest);
            $id = $_SESSION['user']->userId;
            $resultset->bindParam(3, $id);
            $resultset->execute();
        }
        return $html;
    }
}