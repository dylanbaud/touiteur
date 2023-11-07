<?php

namespace iutnc\touiteur\dispatch;
use iutnc\touiteur\actions as AC;

class Dispatcher
{

    public string $action;

    public function __construct(string $action)
    {
        $this->action = $action;
    }

    public function run(): void
    {
        $class = null;

        switch ($this->action)
        {
            case 'sign-in':
                $class = new AC\SigninAction();
                break;
            case 'sign-up':
                $class = new AC\SignupAction();
                break;
            case 'sign-out':
                $class = new AC\SignoutAction();
                break;
            default:
                $class = new AC\DefaultAction();
                break;
        }

        $this->renderPage($class->execute());
    }

    private function renderPage(string $html): void
    {
        $title = 'Touiteur';
        if ($this->action == 'sign-in') {
            $title .= ' | Connexion';
        } else if ($this->action == 'sign-up') {
            $title .= ' | Inscription';
        } else if ($this->action == 'sign-out') {
            $title .= ' | Déconnexion';
        } else if ($this->action == '') {
            $title .= ' | Accueil';
        }

        print '
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./styles.css">
    <link rel="icon" href="./img/logo.png">
    <title>'.$title.'</title>
</head>
<body>
<div class="navbar">
    <nav>
        <a href="?action=" class="logo"><img src="/img/logo.png" alt="Logo"></a>
        <a href="?action="><i class="fa-solid fa-house"></i>Accueil</a>
        <a href=""><i class="fa-solid fa-magnifying-glass"></i>Rechercher</a>
        <a href="?action=sign-in"><i class="fa-solid fa-user"></i>Compte</a>
        <a href="" class="post">Poster</a>
    </nav>
</div>

' . $html . '

<div class="right"></div>

<script src="https://kit.fontawesome.com/84d125ec8a.js" crossorigin="anonymous"></script>
</body>
</html>';
    }
}
