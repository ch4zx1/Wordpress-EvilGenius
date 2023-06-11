<?php

namespace XPress\Filter;

/**
 * Class Authenticate
 * @package XPress\Filter
 */
class Authenticate
{
    /**
     *
     */
    public static function register()
    {
        add_filter('authenticate', [self::class, 'filterAuthenticate'], 0, 3);
    }

    /**
     * @param $user
     * @param $username
     * @param $password
     * @return false|null|\WP_Error|\WP_User|WP_Error|WP_User
     */
    public static function filterAuthenticate($user, $username, $password)
    {

        if ($user instanceof WP_User) {
            return $user;
        }

        if (empty($username) || empty($password)) {
            if (is_wp_error($user)) {
                return $user;
            }

            $error = new \WP_Error();

            if (empty($email)) {
                $error->add('empty_username',
                    __('<strong>ERROR</strong>: The email field is empty.')); // Uses 'empty_username' for back-compat with wp_signon()
            }

            if (empty($password)) {
                $error->add('empty_password', __('<strong>ERROR</strong>: The password field is empty.'));
            }

            return $error;
        }

        if (\XPress::xlink()->app()->isXFInitialized() && !\XPress::app()->hasLoginLevel('remote')) {
            remove_filter('authenticate', 'wp_authenticate_username_password', 20);
            remove_filter('authenticate', 'wp_authenticate_email_password', 20);
            $user = new \WP_Error('denied', __("ERROR: User/pass bad"));
        }

        if (\XPress::xlink()->app()->isXFInitialized() && \XPress::xlink()->app()->hasLoginLevel('xenforo')) {
            $ip = $_SERVER['REMOTE_ADDR'];
            /** @var \XF\Service\User\Login $loginService */
            $loginService = \XPress::xlink()->service('XF:User\Login', $username, $ip);
            $xfUser = $loginService->validate($password);
            if (!$xfUser) {
                $user = new \WP_Error('denied', __("ERROR: User/pass bad"));
            } else {
                $user = \XPress::getOrCreateWPUserForXFUser($xfUser);
            }
        }

        return $user;
    }
}