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
<html>
<head>
    <title>Touiteur</title>
</head>
<body>
<h1>Accueil Touiteur</h1>
</body>
</html>';
        print $html;
    }

}
