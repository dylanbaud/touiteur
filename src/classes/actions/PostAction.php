<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\auth\Auth;
use iutnc\touiteur\db\ConnectionFactory;

class PostAction extends Action
{

    public function execute(): string
    {
        $db = ConnectionFactory::makeConnection();
        $html = '';

        if ($this->http_method === 'GET' && !Auth::isLogged()) {
            $html .= <<<HTML
                <div class="publier">
                    <form method="post" action="?action=create-post">
                        <h2>Publier un poste</h2>
                        <input type="text" name="texte-post" id="texte-post" required placeholder="Écrivez un texte...">
                        
                        <input type="submit" value="Poster" class="submit">
                        <p><a href="?action=sign-up">Annuler...</a></p>
                    </form>
                </div>
HTML;

        } elseif ($this->http_method === 'POST' && Auth::isLogged()) {
            $texte_post = filter_var($_POST['texte-post'], FILTER_SANITIZE_STRING);
            $png_post = "";
            $query = "insert into USER values (?, ?, 0, date('Y-m-d'), ?)";
            $resultset = $db->prepare($query);
            $resultset->bindParam(0, $texte_post);
            $resultset->bindParam(1, $png_post);
            $resultset->bindParam(1, $_SESSION["user"]->userId);
        }

        return $html;
    }
}