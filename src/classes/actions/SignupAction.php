<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\auth\Auth;
use iutnc\touiteur\AuthException;

class SignupAction extends Action
{

    public function execute(): string
    {
        $html = '';
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $html .= '
                <form method="post" action="?action=add-user">
                    <label for="email">Email:</label>
                    <input type="text" name="email" id="email" required><br>
                    
                    <label for="password">Mot de passe:</label>
                    <input type="password" name="password" id="password" required><br>
                    <input type="submit" value="Inscription">
                </form>';
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
            try {
                Auth::register($email, $password);
                $html .= 'Inscription réussie';
            } catch (AuthException $e) {
                $html .= 'Inscription refusé';
            }
        }
        return $html;
    }
}

