<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\auth\Auth;
use iutnc\touiteur\db\ConnectionFactory;
use SplFileInfo;

class CreatePostAction extends Action
{

    public function execute(): string
    {
        $db = ConnectionFactory::makeConnection();
        $html = '';

        if ($this->http_method === 'GET' && Auth::isLogged()) {
            $html .= <<<HTML
                <div class="publier">
                    <form method="post" action="?action=create-post" enctype='multipart/form-data'>
                        <h2>Publier</h2>
                        
                        <input type="text" name="text" id="text" required placeholder="Écrivez un texte...">
                        
                        <label for="image">Ajoutez une image:</label>
                        <input type="file" name="inputfile">
                        
                        <input type="submit" value="Poster" class="submit">
                        
                    </form>
                </div>
HTML;

        } elseif ($this->http_method === 'POST' && Auth::isLogged()) {

            $text = filter_var($_POST['text'], FILTER_SANITIZE_STRING);

            $upload_dir = 'img/post/';
            $filename = uniqid();

            print $_FILES['inputfile']['type'];

            $tmp = $_FILES['inputfile']['tmp_name'];
            if (($_FILES['inputfile']['error'] === UPLOAD_ERR_OK) && ($_FILES['inputfile']['type'] === 'image/png' || $_FILES['inputfile']['type'] === 'image/jpeg' || $_FILES['inputfile']['type'] === 'image/gif') && ($_FILES['inputfile']['size'] < 20000000)) {

                $dest = $upload_dir . $filename . '.png';
                if (!move_uploaded_file($tmp, $dest)) {
                    print "hum, hum téléchargement non valide<br>";
                } else {
                    print "echec du téléchargement ou type non autorisé<br>";
                }
            }

            $query = 'insert into POST (postText, image, postDate, score, userId) VALUES (?, ? , ?, 0, ?)';
            $resultset = $db->prepare($query);
            $resultset->bindParam(1, $text);
            $resultset->bindParam(2, $dest);
            $date = date('Y-m-d');
            $resultset->bindParam(3, $date);
            $id = $_SESSION['user']->userId;
            $resultset->bindParam(4, $id);
            $resultset->execute();
        }
        return $html;
    }
}