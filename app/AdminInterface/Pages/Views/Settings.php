<?php

namespace CodeForceInfiniteScroll\App\AdminInterface\Pages\Views;

use CodeForceInfiniteScroll\App\Templates;

class Settings 
{
    private $template = 'settings';

    public function __construct($Controller, $Model) {
        //
    }

    public function display() 
    {
        $TemplatesRenderer = new Templates\Renderer;
        echo $TemplatesRenderer->render($this->template);
    }
}
