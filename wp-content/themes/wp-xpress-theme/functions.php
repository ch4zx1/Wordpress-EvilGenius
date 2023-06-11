<?php

if(!class_exists('\XPress')) {
    if(!is_admin()) {
        echo "The XPress theme may not be activated without the XPress plugin. Active the XPress plugin in your <a href=" . admin_url() . ">Dashboard</a> first.";
    }
    require get_parent_theme_file_path('/inc/dummyClass.php');
}

require get_parent_theme_file_path('/inc/template-tags.php');
require get_parent_theme_file_path('/inc/filters.php');
require get_parent_theme_file_path('/inc/actions.php');

/** --------- */
/** WIDGETS */
/** --------- */

/** REGISTER NEW WIDGETS */
$templateDir = get_template_directory();
foreach(scandir("{$templateDir}/widgets") as $widget) {
    $file = "{$templateDir}/widgets/{$widget}";
    if(is_file($file)) {
        include_once($file);
    }
}