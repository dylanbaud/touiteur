<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\auth\Auth;
use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\exception\AuthException;
use iutnc\touiteur\user\User;
use PDO;

class SigninAction extends Action
{

    public function execute(): string
    {
        $db = ConnectionFactory::makeConnection();
        $html = '';
        if ($this->http_method === 'GET' && !Auth::isLogged()) {
            $html .= <<<HTML
                <div class="sign">
                    <form method="post" action="?action=sign-in">
                        <h2>Connexion</h2>
                        <label for="email">Email:</label>
                        <input type="text" name="email" id="email" required placeholder="exemple@mail.com">
                        
                        <label for="password">Mot de passe:</label>
                        <input type="password" name="password" id="password" required placeholder="dylansmashunpeunon">
                        <input type="submit" value="Se connecter" class="submit">
                        <p>Pas encore inscrit ? <a href="?action=sign-up">Inscrivez-vous</a></p>
                    </form>
                </div>
HTML;
        } elseif ($this->http_method === 'POST' && !Auth::isLogged()) {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

            try {
                Auth::authentificate($email, $password);
                $query = 'select * from USER where email = ?';
                $resultset = $db->prepare($query);
                $resultset->bindParam(1, $email);
                $resultset->execute();
                $row = $resultset->fetch(PDO::FETCH_ASSOC);
                $_SESSION['user'] = User::getUser($row['userId']);
                $id = $_SESSION['user']->userId;
                header("Location: index.php?action=view-profile&id=$id");
            } catch (AuthException $e) {
                $html .= <<<HTML
                    <div class="default">
                        <h2>Erreur d'authentification</h2>
                    </div>
                HTML;
            }

        } else if (Auth::isLogged()) {
            $id = $_SESSION['user']->userId;
            header("Location: index.php?action=view-profile&id=$id");
        }
        return $html;
    }
}

