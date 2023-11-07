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

        if ($this->http_method === 'GET' && !Auth::isLogged()) {
            $html .= <<<HTML
                <div class="publier">
                    <form method="post" action="?action=create-post" enctype='multipart/form-data'>
                        <h2>Publier</h2>
                        
                        <input type="text" name="text" id="text" required placeholder="Écrivez un texte...">
                        
                        <label for="image">Ajoutez une image:</label>
                        <input type="file" name="image">
                        
                        <input type="submit" value="Poster" class="submit">
                        
                    </form>
                </div>
HTML;

        } elseif ($this->http_method === 'POST' && Auth::isLogged()) {
            $text = filter_var($_POST['text'], FILTER_SANITIZE_STRING);



//            $png_post = "";
//            $query = "insert into POST values (?, ?, 0, date('Y-m-d'), ?)";
//            $resultset = $db->prepare($query);
//            $resultset->bindParam(0, $texte_post);
//            $resultset->bindParam(1, $png_post);
//            $resultset->bindParam(1, $_SESSION["user"]->userId);
        }

        return $html;
    }
}