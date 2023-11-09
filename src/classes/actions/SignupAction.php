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
                
                    <div class="inputBox">
                        <input type="text" name="username" id="username" required>
                        <span>Pseudo</span>
                    </div>
                    
                    <div class="name">
                        <div class="inputBox">
                            <input type="text" name="firstname" id="firstname" required>
                            <span>Prénom</span>
                        </div>
                        
                        <div class="inputBox">
                            <input type="text" name="lastname" id="lastname" required>
                            <span>Nom</span>
                        </div>
                    </div>
                    
                    <div class="inputBox">
                        <input type="date" name="birthday" id="birthday" required>
                    </div>
                    
                    <div class="inputBox">
                        <input type="email" name="email" id="email" required>
                        <span>Email</span>
                    </div>
                    
                    <div class="inputBox">
                        <input type="password" name="password" id="password" required>
                        <span>Mot de passe</span>
                    </div>
                    
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
                    <h2>Inscription réussie</h2>
                </div>
<div class="right"></div>
HTML;
            } catch (AuthException $e) {
                $html .= <<<HTML
                <div class="default">
                    <h2>Inscription refusée</h2>
                </div>
<div class="right"></div>
HTML;
            }
        } else if (Auth::isLogged()) {
            $id = $_SESSION['user']->userId;
            header("Location: index.php?action=view-profile&id=$id");
        }
        return $html;
    }
}

