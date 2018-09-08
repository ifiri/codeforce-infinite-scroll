<?php
/*
Plugin Name: CodeForce Infinite Scroll
Description: Provide lazy ajax loading for custom queries
Text Domain: codeforce-infinite-scroll
Author: Raymond Costner
Version: 1.0.0
License: GPL 3.0
*/

namespace CodeForceInfiniteScroll;

// Necessary constants
define(__NAMESPACE__ . '\\PLUGIN_FILE', __FILE__);
define(__NAMESPACE__ . '\\PLUGIN_PATH', dirname(PLUGIN_FILE));
define(__NAMESPACE__ . '\\PLUGIN_FOLDER', basename(PLUGIN_PATH));

function run() 
{
    spl_autoload_register(__NAMESPACE__ . '\\autoload');

    add_action('plugins_loaded', array(new App\Main, 'init'));
}

function autoload($class_name) 
{
    if (strpos($class_name, __NAMESPACE__) === false) {
        return;
    }

    // Parse passed absolute class name
    $class_name = str_replace('App', 'app', $class_name);
    $class_path_pieces = explode('\\', $class_name);
    array_shift($class_path_pieces);

    // Get Path/To/Class
    $absolute_class_name = PLUGIN_PATH . '/' . implode('/', $class_path_pieces) . '.php';

    require $absolute_class_name;
}

// Start the app
run();
