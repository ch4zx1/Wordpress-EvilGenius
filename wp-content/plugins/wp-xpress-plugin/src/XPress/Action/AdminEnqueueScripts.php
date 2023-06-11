<?php

namespace XPress\Action;

/**
 * Class AdminEnqueueScripts
 * @package XPress\Action
 */
class AdminEnqueueScripts
{
    /**
     *
     */
    public static function register()
    {
        add_action('admin_enqueue_scripts', [self::class, 'actionAdminEnqueueScripts'], 10, 0);
    }

    /**
     */
    public static function actionAdminEnqueueScripts()
    {
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('thxpress-color-picker-init', plugins_url('js/color-picker-init.js', __FILE__),
            array('wp-color-picker'), false, true);
    }
}