<?php

namespace XPress\Action;

/**
 * Class WpLoaded
 * @package XPress\Action
 */
class WpLoaded
{
    /**
     *
     */
    public static function register()
    {
        add_action('wp_loaded', [self::class, 'actionWpLoaded']);
    }

    /**
     */
    public static function actionWpLoaded()
    {
        $baseUrl = \XPress::xlink()->xfApp()->options()->boardUrl;
        wp_register_style('xpress_wp_admin_style', $baseUrl . '/css.php?css=public:thxpress_avatars.less');
        wp_enqueue_style('xpress_wp_admin_style');
    }
}