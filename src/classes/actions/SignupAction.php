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
                </form>';
            </div>
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
                $_SESSION['user'] = new User($email);
                $html .= <<<HTML
                <div class="default">
                    <h2>Inscription réussi</h2>
                </div>
HTML;
            } catch (AuthException $e) {
                $html .= <<<HTML
                <div class="default">
                    <h2>Inscription refusé</h2>
                </div>
HTML;
            }
        } else if (Auth::isLogged()) {
            $email = $_SESSION['user']->email;
            $html .= <<<HTML
            <div class="default">
                <h2>Bonjour $email</h2>
            </div>
HTML;
        }
        return $html;
    }
}

