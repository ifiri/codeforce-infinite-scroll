<?php

namespace CodeForceInfiniteScroll\App\AdminInterface\Pages\Models;

class Settings 
{
    public function get_page_params() 
    {
        return array(
            'menu_title' => __('- Users', 'codeforce-infinite-scroll'),
            'page_title' => __('CodeForce Infinite Scroll', 'codeforce-infinite-scroll'),

            'slug' => 'cfis-settings',
            'parent' => 'codeforce-infinite-scroll',

            'capability' => 'edit_dashboard',
        );
    }
}
