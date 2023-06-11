<?php

namespace XLink;

class App
{
    protected $options;
    protected $xfInitialized = false;

    public function __construct($appOptions)
    {
        $this->options = $appOptions;
    }

    /** @var Registry */
    protected $registry;

    public function isXFInitialized()
    {
        return $this->xfInitialized;
    }

    public function isXFConnectionBroken()
    {
        $rootPath = $this->options['rootPath'];

        if (!$this->isXFInitialized() && !$rootPath) {
            return true;
        }

        return false;
    }

    public function options()
    {
        return $this->options;
    }

    public function option($key)
    {
        $options = $this->options();

        if (isset($options[$key])) {
            return $options[$key];
        }

        return null;
    }

    public function setOption($key, $value) {
        $this->options[$key] = $value;
    }

    public function registry()
    {
        if (!$this->registry) {
            $this->registry = new Registry($this);
        }

        return $this->registry;
    }

    public function initializeXenForo()
    {
        $options = $this->options();

        if (!isset($options['rootPath'])) {
            return false;
        }

        if (!file_exists($options['rootPath'] . '/src/XF.php')) {
            return false;
        }

        // Emergency bypass in case something breaks
        if (defined('XLINK_BYPASS_XENFORO') && XLINK_BYPASS_XENFORO == true
            || empty($options['rootPath'])
            || !file_exists($options['rootPath'] . '/src/XF.php')) {
            var_dump(false);
            return null;
        }

        /** @noinspection PhpIncludeInspection */
        require_once $options['rootPath'] . '/src/XF.php';

        \XF::start($options['rootPath']);
        $app = \XF::setupApp('ThemeHouse\XLink\App');
        $cacheResponse = $app->start();

        if($cacheResponse) {
            $cacheResponse->send(\XF::app()->request());
            exit;
        }

        $this->xfInitialized = true;

        $this->options['xf'] = \XF::options();

        if (!defined('XLINK_BYPASS_XENFORO')) {
            define('XLINK_BYPASS_XENFORO', true);
        }

        return true;
    }

    public function runApp($output, $responseCode = null)
    {
        if (!$this->isXFInitialized()) {
            echo $output;
            exit;
        }

        ob_start();
        /** @var \ThemeHouse\XLink\App $app */
        $app = \XF::app();

        $app->setOutput($output, $responseCode);

        $response = $app->run();
        $response->httpCode($app->getResponseCode());

        $extraOutput = ob_get_clean();
        if (strlen($extraOutput)) {
            $body = $response->body();
            if (is_string($body)) {
                if ($response->contentType() == 'text/html') {
                    if (strpos($body, '<!--XF:EXTRA_OUTPUT-->') !== false) {
                        $body = str_replace('<!--XF:EXTRA_OUTPUT-->', $extraOutput . '<!--XF:EXTRA_OUTPUT-->', $body);
                    } else {
                        $body = preg_replace('#<body[^>]*>#i', "\\0$extraOutput", $body);
                    }
                    $response->body($body);
                } else {
                    $response->body($extraOutput . $body);
                }
            }
        }

        $response->send($app->request());
    }

    public function hasLoginLevel($level) {
        $config = $this->option('xlink');
        $loginLevel = isset($config['login_level']) ? $config['login_level'] : -1;

        switch($level) {
            case 'sso':
                return $loginLevel == 3;

            case 'xenforo':
                return $loginLevel >= 1;

            case 'remote':
                return $loginLevel <= 1;

            default:
                return false;
        }
    }
}