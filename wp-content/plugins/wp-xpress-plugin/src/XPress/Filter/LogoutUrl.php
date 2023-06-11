<?php

namespace XPress\Filter;

/**
 * Class LogoutUrl
 * @package XPress\Filter
 */
class LogoutUrl
{
    /**
     *
     */
    public static function register()
    {
        add_filter('logout_url', [self::class, 'filterLogoutUrl'], 10, 2);
    }

    /**
     * @param $logoutUrl
     * @param $redirect
     * @return mixed|string
     */
    public static function filterLogoutUrl($logoutUrl, $redirect) {
        $xfUser = \XPress::getXFUser();

        if (\XPress::xlink()->app()->hasLoginLevel('sso') && $xfUser) {
            return \XPress::xlink()->buildLink('logout', null, ['t' => \XF::app()->get('csrf.token')]);
        }

        return $logoutUrl;
    }
}