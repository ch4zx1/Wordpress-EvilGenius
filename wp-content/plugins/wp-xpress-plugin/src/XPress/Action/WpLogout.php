<?php

namespace XPress\Action;

/**
 * Class WpLogout
 * @package XPress\Action
 */
class WpLogout
{
    /**
     *
     */
    public static function register()
    {
        add_action('wp_logout', [self::class, 'actionWpLogout'], PHP_INT_MAX);
    }

    /**
     */
    public static function actionWpLogout()
    {
        wp_redirect(home_url());
        exit;
    }
}