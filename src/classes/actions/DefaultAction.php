<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\actions\Action;

class DefaultAction extends Action
{

    public function execute(): string
    {
        return <<<HTML
            <div class="default">
                <h1>Bienvenue !</h1>    
            </div>
            HTML;
    }
}