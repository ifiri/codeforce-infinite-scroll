<?php

namespace CodeForceInfiniteScroll\App\AdminInterface\Pages\Controllers;

class Settings 
{
    public function __construct($Model) 
    {
        $this->Model = $Model;
    }

    public function execute() 
    {
        $this->View->display();
    }

    public function set_view($View) {
        $this->View = $View;
    }

    public function register_page() {
        $params = $this->Model->get_page_params();

        add_submenu_page(
            $params['parent'],
            $params['page_title'], 
            $params['menu_title'], 
            $params['capability'], 
            $params['slug'], 
            [$this, 'execute']
        );
    }
}
