<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\auth\Auth;
use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\user\User;
use PDO;

class SettingsAction extends Action
{

    public function execute(): string
    {
        $db = ConnectionFactory::makeConnection();
        $html = "";
        if ($this->http_method === 'GET' && Auth::isLogged()) {
            $html .= <<<HTML
    <div class="sign sign-settings">
        <form method="post" action="?action=settings" enctype='multipart/form-data'>
            <h2>Informations</h2>
            
            <label for="profilePic" style="cursor: pointer; align-self: center;">
                <img src="{$_SESSION['user']->profilePic}" alt="Photo de profil">
            </label>
            <input type="file" id="profilePic" name="profilePic" value="{$_SESSION['user']->profilePic}">
            
            <div class="inputBox">
                <input type="text" name="username" id="username" value="{$_SESSION['user']->username}">
                <span>Pseudo</span>
            </div>
            
            <div class="inputBox">
                <input type="text" name="firstname" id="firstname" value="{$_SESSION['user']->firstName}">
                <span>Prénom</span>
            </div>
            
            <div class="inputBox">
                <input type="text" name="lastname" id="lastname" value="{$_SESSION['user']->lastName}">
                <span>Nom</span>
            </div>
            
            <div class="inputBox">
                <input type="date" name="birthday" id="birthday" value="{$_SESSION['user']->birthDate}" required>
                <span>Anniversaire</span>
            </div>
            
            <input type="submit" class="submit" value="Modifier">
        </form>
    </div>
<div class="right">
HTML;

        } else if ($this->http_method === 'POST' && Auth::isLogged()) {

            if (isset($_FILES['profilePic']) && $_FILES['profilePic']['size'] <= 10000000) {
                if(file_exists($_SESSION['user']->profilePic) && $_SESSION['user']->profilePic != "./img/defaultProfile.png")
                    unlink($_SESSION['user']->profilePic);
                $type = $_FILES['profilePic']['type'];
                if(explode('/', $type)[0] == 'image'){
                    $extension = ".".explode('/', $type)[1];
                    $origine = $_FILES['profilePic']['tmp_name'];
                    $nom = uniqid() . $extension;
                    $destination = "./img/profile/$nom";
                    move_uploaded_file($origine, $destination);
                    $profilePic = $destination;
                } else{
                    $profilePic = $_SESSION['user']->profilePic;
                }

            } else {
                $profilePic = $_SESSION['user']->profilePic;
            }

            if (!isset($_POST['username'])) {
                $username = $_SESSION['user']->username;
            } else {
                $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
                $query = 'select count(*) from USER where username = ?';
                $resultset = $db->prepare($query);
                $resultset->bindParam(1, $username);
                $resultset->execute();
                $row = $resultset->fetch(PDO::FETCH_ASSOC);
                if ($row['count(*)'] != 0) {
                    $username = $_SESSION['user']->username;
                }
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
<div class="right">
HTML;
        }
        return $html;
    }
}