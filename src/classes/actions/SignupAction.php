<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\auth\Auth;
use iutnc\touiteur\exception\AuthException;

class SignupAction extends Action
{

    public function execute(): string
    {
        $html = '';
        if ($this->http_method === 'GET') {
            $html .= <<<HTML
            <div class="sign sign-up">
                <form method="post" action="?action=sign-up">
                    <h2>Inscription</h2>
                
                    <input type="text" name="username" id="username" required placeholder="Pseudo"><br>
                    
                    <div class="name">
                        <input type="text" name="firstname" id="firstname" required placeholder="Prénom"><br>
                        
                        <input type="text" name="lastname" id="lastname" required placeholder="Nom"><br>
                    </div>
                    
                    <input type="date" name="birthday" id="birthday" required placeholder="Date de naissance"><br>
                    
                    <input type="text" name="email" id="email" required placeholder="Email"><br>
                    
                    <input type="password" name="password" id="password" required placeholder="Mot de passe"><br>
                    <input type="submit" value="Inscription" class="submit">
                </form>';
            </div>
HTML;

        } elseif ($this->http_method === 'POST') {
            $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
            $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
            $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
            $birthday = filter_var($_POST['birthday'], FILTER_SANITIZE_STRING);
            try {
                Auth::register($username, $firstname, $lastname, $email, $password, $birthday);
                $html .= 'Inscription réussie';
            } catch (AuthException $e) {
                $html .= 'Inscription refusé';
            }
        }
        return $html;
    }
}

