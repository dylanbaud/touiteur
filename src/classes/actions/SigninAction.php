<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\auth\Auth;
use iutnc\touiteur\AuthException;
use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\user\User;
use PDO;

class SigninAction extends Action
{

    public function execute(): string
    {
        $db = ConnectionFactory::makeConnection();
        $html = '';
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $html .= '
                <form method="post" action="?action=sign-in">
                    <label for="email">Email:</label>
                    <input type="text" name="email" id="email" required><br>
                    
                    <label for="password">Mot de passe:</label>
                    <input type="password" name="password" id="password" required><br>
                    <input type="submit" value="Connexion">
                </form>';
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

            try {
                Auth::authentificate($email, $password);
                $query = 'select * from USER where email = ?';
                $resultset = $db->prepare($query);
                $resultset->bindParam(1, $email);
                $resultset->execute();
                $row = $resultset->fetch(PDO::FETCH_ASSOC);

                $user = new User($row['email']);

                foreach ($user->getPosts() as $post) {
                    $html .= $post . '<br>';
                }

            } catch
            (AuthException $e) {
                $html .= "Erreur lors de la connexion";
            }

        }
        return $html;
    }
}

