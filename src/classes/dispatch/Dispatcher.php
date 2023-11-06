<?php

namespace iutnc\touiteur\dispatch;

class Dispatcher
{

    public string $action;

    public function __construct(string $action)
    {
        $this->action = $action;
    }

    public function run(): void
    {
        switch ($this->action) {
            default :

        }
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
