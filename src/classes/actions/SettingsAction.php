<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\auth\Auth;
use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\user\User;

class SettingsAction extends Action
{

    public function execute(): string
    {
        $db = ConnectionFactory::makeConnection();
        $html = "";
        if ($this->http_method === 'GET' && Auth::isLogged()) {
            $html .= <<<HTML
                <div class="sign">
                    <form method="post" action="?action=settings" enctype='multipart/form-data'>
                    
                        <h2>Changer vos informations</h2>
                        <img src="{$_SESSION['user']->profilePic}" alt="Photo de profil">
                        <label for="profilePic">Photo de profil:</label>
                        <input type="file" name="profilePic">
                        <label for="username">Nom d'utilisateur:</label>
                        <input type="text" name="username" id="username" value="{$_SESSION['user']->username}">
                        <label for="firstname">Prénom:</label>
                        <input type="text" name="firstname" id="firstname" value="{$_SESSION['user']->firstName}">
                        <label for="lastname">Nom:</label>
                        <input type="text" name="lastname" id="lastname" value="{$_SESSION['user']->lastName}">
                        <label for="birthday">Date de naissance:</label>
                        <input type="date" name="birthday" id="birthday" value="{$_SESSION['user']->birthDate}" required>
                        <button type="submit" class="submit">Modifier</button>
                    </form>
                </div>
        HTML;

        } else if ($this->http_method === 'POST' && Auth::isLogged()) {

            if (isset($_FILES['profilePic']) && $_FILES['profilePic']['size'] <= 10000000) {
                if(file_exists($_SESSION['user']->profilePic) && $_SESSION['user']->profilePic != "./img/defaultProfile.png")
                    unlink($_SESSION['user']->profilePic);
                $type = $_FILES['profilePic']['type'];
                if(explode('/', $type)[0] == 'image'){
                    $extension = explode('/', $type)[1];
                    $origine = $_FILES['profilePic']['tmp_name'];
                    $nom = uniqid() . $extension;
                    $destination = "./img/profile/$nom";
                    move_uploaded_file($origine, $destination);
                    $profilePic = $destination;
                }

            } else {
                $profilePic = $_SESSION['user']->profilePic;
            }

            if (!isset($_POST['username'])) {
                $username = $_SESSION['user']->username;
            } else {
                $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            }

            if (!isset($_POST['firstname'])) {
                $firstname = $_SESSION['user']->firstName;
            } else {
                $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
            }

            if (!isset($_POST['lastname'])) {
                $lastname = $_SESSION['user']->lastName;
            } else {
                $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
            }

            $id = $_SESSION['user']->userId;

            $query = "UPDATE USER SET username = ?, firstName = ?, lastName = ?, birthDate = ?, profilePic = ? WHERE userId = ?";
            $resultset = $db->prepare($query);
            $resultset->bindParam(1, $username);
            $resultset->bindParam(2, $firstname);
            $resultset->bindParam(3, $lastname);
            $resultset->bindParam(4, $_POST['birthday']);
            $resultset->bindParam(5, $profilePic);
            $resultset->bindParam(6, $id);
            echo $_SESSION['user']->userId;
            $resultset->execute();
            $_SESSION['user'] = User::getUser($id);
            $html = <<<HTML
                <div class="default">
                    <h2>Modifications réussies</h2>
                </div>
            HTML;
        }
        return $html;
    }
}