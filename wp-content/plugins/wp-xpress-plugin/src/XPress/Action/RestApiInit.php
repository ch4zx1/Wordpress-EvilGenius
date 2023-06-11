<?php

namespace XPress\Action;

/**
 * Class Init
 * @package XPress\Action
 */
/**
 * Class RestApiInit
 * @package XPress\Action
 */
class RestApiInit
{
    /**
     * @var null
     */
    protected static $outwardsIp = null;

    /**
     * @var array
     */
    protected static $apiEndpoints = ['Settings', 'User', 'Users', 'Roles', 'UserStats', 'Widget', 'WidgetList'];

    /**
     *
     */
    public static function register()
    {
        add_action('init', [self::class, 'actionInit'], 0, 0);
        add_action('rest_api_init', [self::class, 'actionRestApiInit'], 0, 0);
    }

    /**
     *
     */
    public static function actionInit()
    {
        global $wp;
        /** @noinspection PhpUndefinedMethodInspection */
        $wp->add_query_var('xf_user');
    }

    /**
     * @throws \Exception
     */
    public static function actionRestApiInit()
    {
        $endpoints = apply_filters('xpress_api_endpoints', self::$apiEndpoints);
        foreach ($endpoints as $endpoint) {
            self::registerAPIEndpoint($endpoint);
        }

        \XPress::$xpressUpdateCycle = true;
        $authenticated = false;

        // Only allow internal requests
        if (in_array($_SERVER['REMOTE_ADDR'], ['::1', '127.0.0.1', self::getOutwardsFacingIP()])) {
            $authenticated = true;
        }

        if (!defined('XLINK_API_PASSWORD') && defined('XPRESS_API_PASSWORD')) {
            define('XLINK_API_PASSWORD', constant('XPRESS_API_PASSWORD'));
        }

        if ((defined('XLINK_API_PASSWORD') && constant('XLINK_API_PASSWORD') && isset($_GET['rest_key']) && constant('XLINK_API_PASSWORD') === $_GET['rest_key'])) {
            $authenticated = true;
        }

        if ($authenticated && !isset($_SERVER['HTTP_X_XPRESSLITEAPIKEY'])) {
            if (defined('XPRESS_API_USER') && constant('XPRESS_API_USER') !== false) {
                $user = get_user_by('ID', constant('XPRESS_API_USER'));
            } else {
                $user = get_users([
                    'role' => 'administrator',
                    'number' => 1
                ]);

                $user = reset($user);
            }

            if ($user) {
                wp_set_current_user(apply_filters('xpress_api_user', $user->ID));
                global $wp_rest_auth_cookie;
                $wp_rest_auth_cookie = false;
            }
        }
    }

    /**
     * @param $endpoint
     * @throws Exception
     * @throws \Exception
     */
    protected static function registerAPIEndpoint($endpoint)
    {
        $classStr = '\XPress\API\\' . $endpoint;
        if (class_exists($classStr)) {
            new $classStr(\XPress::app());
        }
    }

    /**
     * @return int|null|string
     */
    protected static function setOutwardsFacingIP()
    {
        $ips = [];

        try {
            $curl = curl_init('https://myexternalip.com/json');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($curl);
            curl_close($curl);
            $data = json_decode($data, true);
            $ip = $data['ip'];

            $ips[] = trim($ip ? $ip : '::1');
        } catch (\Exception $e) {
            $ips[] = '::1';
        }

        try {
            $curl = curl_init('https://api.ipify.org/?format=json');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($curl);
            curl_close($curl);
            $data = json_decode($data, true);
            $ip = $data['ip'];

            $ips[] = trim($ip ? $ip : '::1');
        } catch (\Exception $e) {
            $ips[] = '::1';
        }

        try {
            $curl = curl_init('https://ipinfo.io/ip');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $ip = curl_exec($curl);
            curl_close($curl);

            $ips[] = trim($ip ? $ip : '::1');
        } catch (\Exception $e) {
            $ips[] = '::1';
        }

        try {
            $curl = curl_init('http://checkip.dyndns.org/');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($curl);
            curl_close($curl);
            preg_match('/(?:\d{1,3}\.){3}\d{1,3}/', $data, $ip);
            $ip = trim(array_shift($ip));

            $ips[] = $ip ? $ip : '::1';
        } catch (\Exception $e) {
            $ips[] = '::1';
        }

        $ips = array_count_values($ips);
        asort($ips);

        end($ips);
        $finalIp = key($ips);
        update_option('xpress_ofip', $finalIp);
        return $finalIp;
    }

    /**
     * @return string
     */
    protected static function getOutwardsFacingIP()
    {
        if (!self::$outwardsIp) {
            $ip = get_option('xpress_ofip', null);

            if (!$ip) {
                $ip = self::setOutwardsFacingIP();
            }

            self::$outwardsIp = $ip;
        }

        return apply_filters('xpress_outwards_facing_ip', self::$outwardsIp);
    }
}