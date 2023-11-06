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
            default:
                $class = new AC\DefaultAction();
                break;
        }

        $this->renderPage($class->execute());
    }

    private function renderPage(string $html): void
    {
        print '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="./styles.css">
    <link rel="icon" href="./img/logo.png">
    <title>Touiteur | Accueil</title>
</head>
<body>
<nav>
    <a href="./index.php"><img src="./img/logo.png" alt="Logo"></a>
    <ul>
        <li><a href="./index.php">Accueil</a></li>
        <li><a href="./index.php?action=sign-in">Connexion</a></li>
    </ul>
</nav>
</body>
</html>';
        print $html;
    }

}
