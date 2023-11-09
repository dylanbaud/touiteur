<?php

namespace iutnc\touiteur\dispatch;

use iutnc\touiteur\actions as AC;

class Dispatcher
{

    public string $action;

    public string $title;

    public string $body;

    public function __construct(string $action)
    {
        $this->action = $action;
        $this->title = 'Touiteur';
        $this->body = '';
    }

    public function run(): void
    {
        $class = null;

        switch ($this->action) {
            case 'sign-in':
                $class = new AC\SigninAction();
                $this->title .= ' | Connexion';
                break;
            case 'sign-up':
                $class = new AC\SignupAction();
                $this->title .= ' | Inscription';
                break;
            case 'sign-out':
                $class = new AC\SignoutAction();
                $this->title .= ' | Déconnexion';
                break;
            case 'view-profile':
                $class = new AC\ViewProfileAction();
                $this->title .= ' | Profil';
                break;
            case 'view-post':
                $class = new AC\ViewPostAction();
                $this->body .= 'style="overflow: hidden;"';
                break;
            case 'create-post':
                $class = new AC\CreatePostAction();
                $this->title .= ' | Post';
                $this->body .= 'style="overflow: hidden;"';
                break;
            case 'settings':
                $class = new AC\SettingsAction();
                $this->title .= ' | Paramètres';
                break;
            case 'view-tag':
                $class = new AC\ViewTagAction();
                $this->title .= ' | Tag';
                break;
            case 'follow-user':
                $class = new AC\FollowUserAction();
                break;
            case 'follow-tag':
                $class = new AC\FollowTagAction();
                break;
            case 'delete-post':
                $class = new AC\DeletePostAction();
                break;
            default:
                $class = new AC\DefaultAction();
                $this->title .= ' | Accueil';
                break;
        }

        $this->renderPage($class->execute());
    }

    private function renderPage(string $html): void
    {
        print '
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./styles.css">
    <link rel="icon" href="/img/logo.png">
    <title>' . $this->title . '</title>
</head>
<body ' . $this->body . '>
<div class="navbar">
    <nav>
        <a href="?action="><img src="./img/icon/home.svg">Accueil</a>
        <a href="?action=sign-in"><img src="./img/icon/account.png">Compte</a>
        <a href="?action=create-post" class="post">Poster</a>
    </nav>
</div>

' . $html . '

</div>

</body>
</html>';
    }
}
