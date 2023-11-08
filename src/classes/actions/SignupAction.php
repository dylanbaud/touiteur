<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\auth\Auth;
use iutnc\touiteur\exception\AuthException;
use iutnc\touiteur\user\User;

class SignupAction extends Action
{

    public function execute(): string
    {
        $html = '';
        if ($this->http_method === 'GET' && !Auth::isLogged()) {
            $html .= <<<HTML
            <div class="sign">
                <form method="post" action="?action=sign-up">
                    <h2>Inscription</h2>
                
                    <input type="text" name="username" id="username" required placeholder="Pseudo">
                    
                    <div class="name">
                        <input type="text" name="firstname" id="firstname" required placeholder="Prénom">
                        
                        <input type="text" name="lastname" id="lastname" required placeholder="Nom">
                    </div>
                    
                    <input type="date" name="birthday" id="birthday" required placeholder="Date de naissance">
                    
                    <input type="text" name="email" id="email" required placeholder="Email">
                    
                    <input type="password" name="password" id="password" required placeholder="Mot de passe">
                    <input type="submit" value="S'inscrire" class="submit">
                    <p>Déjà inscrit ? <a href="?action=sign-in">Connectez-vous</a></p>
                </form>
            </div>
<div class="right"></div>
HTML;
        } else if ($this->http_method === 'POST' && !Auth::isLogged()) {
            $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
            $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
            $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
            $birthday = filter_var($_POST['birthday'], FILTER_SANITIZE_STRING);
            try {
                Auth::register($username, $firstname, $lastname, $email, $password, $birthday);
                $html .= <<<HTML
                <div class="default">
                    <h2>Inscription réussi</h2>
                </div>
<div class="right"></div>
HTML;
            } catch (AuthException $e) {
                $html .= <<<HTML
                <div class="default">
                    <h2>Inscription refusé</h2>
                </div>
<div class="right"></div>
HTML;
            }
        } else if (Auth::isLogged()) {
            $id = $_SESSION['user']->userId;
<<<<<<< HEAD
            header("Location: index.php?action=view-profile&id=$id");
=======
            $html .= <<<HTML
            <div class="default">
                <h2>Bonjour $username</h2>
                <a href="?action=view-profile&id=$id">Accéder au compte</a>
            </div>
<div class="right">
HTML;
>>>>>>> ff307ad6c7ab41e7c3c3c8ce9ff0fce34778c479
        }
        return $html;
    }
}

