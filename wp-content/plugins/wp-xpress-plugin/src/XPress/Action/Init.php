<?php

namespace XPress\Action;

/**
 * Class Init
 * @package XPress\Action
 */
class Init
{
    /**
     *
     */
    public static function register()
    {
        if(\XPress::app()->hasLoginLevel('sso')) {
            add_action('init', [self::class, 'actionInit'], 0);
            add_action('init', [self::class, 'actionSSO']);
        }
    }

    /**
     *
     */
    public static function actionInit() {
        if (!defined('REST_REQUEST') || !REST_REQUEST) {
            if (isset($_GET['action']) && $_GET['action'] == 'logout') {
                return;
            } else {
                if (!is_user_logged_in()) {
                    $user = \XPress::authenticateFromXF();
                    if (isset($user->ID) && intval($user->ID) > 0) {
                        clean_user_cache($user->ID);

                        wp_clear_auth_cookie();
                        wp_set_current_user($user->ID);
                        wp_set_auth_cookie($user->ID, true, is_ssl());

                        update_user_caches($user);

                        if (is_user_logged_in()) {
                            wp_set_current_user($user->ID);
                        }
                    }
                } else {
                    $xfUser = \XPress::getXFUser();
                    $visitor = \XPress::xlink()->visitor();

                    if($xfUser && !$visitor->user_id) {
                        wp_logout();
                        wp_set_current_user(0);
                        return;
                    }

                    if (!$xfUser || !$visitor->user_id) {
                        return;
                    }

                    if ($visitor->user_id != $xfUser->user_id) {
                        wp_logout();
                        wp_set_current_user(0);
                    }
                }
            }
        }
    }

    public static function actionSSO() {
        // Store for checking if this page equals wp-login.php
        $page_viewed = substr(basename($_SERVER['REQUEST_URI']), 0, 12);

        if ($page_viewed == "wp-login.php") {
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'logout':
                    case 'postpass':
                        break;

                    case 'register':
                        wp_redirect(\XPress::xlink()->buildLink('register'));
                        exit;

                    default:
                    case 'login':
                        wp_redirect(\XPress::xlink()->buildLink('login'));
                        exit;
                }
            } else {
                if (wp_get_current_user()->ID == 0) {
                    wp_redirect(\XPress::xlink()->buildLink('login'));
                    exit;
                } else {
                    if (isset($_GET['redirect_to'])) {
                        $url = html_entity_decode($_GET['redirect_to']);
                        wp_redirect($url);
                        exit;
                    }
                }
            }
        }
    }
}