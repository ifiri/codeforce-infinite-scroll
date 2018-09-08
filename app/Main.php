<?php

namespace CodeForceInfiniteScroll\App;

use CodeForceInfiniteScroll\App\AdminInterface\Pages;

class Main {
    public function init() 
    {
        $this->init_language();
        $this->add_shortcode();

        // Add assets
        add_action('wp_enqueue_scripts', [$this, 'add_client_assets'], 100);
        add_action('wp_enqueue_scripts', [$this, 'localize_client_assets'], 100);

        // Ajax actions
        $this->add_ajax_actions();

        $this->init_admin_interface();
    }

    private function init_admin_interface() 
    {
        $SettingsModel = new Pages\Models\Settings;

        $SettingsController = new Pages\Controllers\Settings($SettingsModel);
        $SettingsView = new Pages\Views\Settings($SettingsController, $SettingsModel);
        
        $SettingsController->setView($SettingsView);

        add_action('admin_menu', [$SettingsController, 'register_page'], 10);
    }

    /**
     * Loop over availaible actions and create instans for every Action
     * Then add private and global hooks
     * 
     * @return null
     */
    private function add_ajax_actions() 
    {
        $ajax_actions = array(
            'LoadMoreUsers' => 'cfis_load_more_users',
            'LoadMorePosts' => 'cfis_load_more_posts',
        );

        foreach ($ajax_actions as $class_name => $action_alias) {
            $action_callable = __NAMESPACE__ . '\\AjaxActions\\' . $class_name;
            $Action = new $action_callable;

            add_action('wp_ajax_' . $action_alias, array($Action, 'execute'));
            add_action('wp_ajax_nopriv_' . $action_alias, array($Action, 'execute'));
        }
    }

    /**
     * Init plugin shortcodes
     * 
     * @return null
     */
    public function add_shortcode() 
    {
        $LoadUsersShortcode = new Shortcodes\CodeForceInfiniteScroll;

        add_shortcode('ajax_load_more_users', [$LoadUsersShortcode, 'shortcode']);
    }

    /**
     * Load language files
     * 
     * @return null
     */
    private function init_language() 
    {
        $languages_folder = \CodeForceInfiniteScroll\PLUGIN_FOLDER . '/languages/';

        load_plugin_textdomain('codeforce-infinite-scroll', false, $languages_folder);
    }

    /**
     * Add styles and scripts
     * 
     * @return null
     */
    public function add_client_assets() 
    {
        $url = plugins_url('/assets/', \CodeForceInfiniteScroll\PLUGIN_FILE);

        wp_enqueue_script(
            'cfis-script', 
            $url . 'main.js',
            ['jquery'],
            '2018-02-14',
            true
        );

        wp_enqueue_style(
            'cfis-style', 
            $url . 'main.css', 
            '2018-02-10'
        );
    }

    /**
     * Pass ajax url and nonce to script
     * 
     * @return null
     */
    public function localize_client_assets() 
    {
        wp_localize_script('cfis-script', 'l10n', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('cfis_nonce'),
        ]);
    }
}