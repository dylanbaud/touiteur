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
    <link rel="stylesheet" href="/styles.css">
    <title>Touiteur | Accueil</title>
</head>
<body>
</body>
</html>';
        print $html;
    }

}
