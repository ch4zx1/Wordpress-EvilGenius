<?php

class XLink
{
    /**
     * @var integer
     */
    protected $time;

    /**
     * @var string
     */
    protected $rootDirectory;

    /**
     * @var string
     */
    protected $sourceDirectory;

    protected $autoLoader;

    /**
     * @var \XLink\App
     */
    protected $app;

    protected $responseCode = 200;

    protected static $instance;

    public function __construct($rootDirectory, $appOptions)
    {
        $this->time = time();
        $this->rootDirectory = $rootDirectory;
        $this->sourceDirectory = __DIR__;
        $this->startAutoloader();

        $this->app = new \XLink\App($appOptions);

        $this->app->initializeXenForo();

        self::$instance = $this;
    }

    protected function startAutoloader()
    {
        if ($this->autoLoader) {
            return;
        }
        /** @noinspection PhpUndefinedClassInspection */
        /** @var \Composer\Autoload\ClassLoader $autoLoader */
        /** @noinspection PhpIncludeInspection */
        $autoLoader = require($this->rootDirectory . '/vendor/autoload.php');
        $autoLoader->register();

        $this->autoLoader = $autoLoader;
    }

    public function __call($name, $arguments)
    {
        return \XF::$name(...$arguments);
    }

    public static function __callStatic($name, $arguments)
    {
        return \XF::$name(...$arguments);
    }

    protected function isXFInitialized()
    {
        return $this->app->isXFInitialized();
    }

    /**
     * @return \XLink\App
     */
    public function app()
    {
        return $this->app;
    }

    public function options()
    {
        return $this->app->options();
    }

    /**
     * @return \XF\App|\XLink\Proxy|\ThemeHouse\XLink\App
     */
    public function xfApp()
    {
        if ($this->isXFInitialized()) {
            return \XF::app();
        }
        return new \XLink\Proxy();
    }

    /**
     * @return \ThemeHouse\XLink\Entity\PlatformLink|\XLink\Proxy
     */
    public function platformLink()
    {
        if ($this->isXFInitialized()) {
            /** @noinspection PhpUndefinedMethodInspection */
            return \XF::app()->getPlatformLink();
        }
        return new \XLink\Proxy();
    }

    public function registry()
    {
        return $this->app->registry();
    }

    public function em()
    {
        if ($this->isXFInitialized()) {
            return \XF::em();
        }
        return new \XLink\Proxy();

    }

    public function finder($shortName)
    {
        if ($this->isXFInitialized()) {
            return \XF::em()->getFinder($shortName);
        }
        return new \XLink\Proxy();

    }

    public function repository($shortName)
    {
        if ($this->isXFInitialized()) {
            return \XF::em()->getRepository($shortName);
        }
        return new \XLink\Proxy();

    }

    public function service($shortName, ...$arguments)
    {
        if ($this->isXFInitialized()) {
            return \XF::service($shortName, ...$arguments);
        }
        return new \XLink\Proxy();

    }

    public function templater()
    {
        if ($this->isXFInitialized()) {
            return \XF::app()->templater();
        }
        return new \XLink\Proxy();

    }

    public function router()
    {
        if ($this->isXFInitialized()) {
            return \XF::app()->router();
        }
        return new \XLink\Proxy();
    }

    public function phrase($name, $params = [], $allowHtml = true)
    {
        if ($this->isXFInitialized()) {
            return \XF::phrase($name, $params, $allowHtml)->render();
        }
        return '';

    }

    public function buildLink($link, $params = [], $extra = [])
    {
        if ($this->isXFInitialized()) {
            $link = explode(':', $link);

            if (count($link) == 2) {
                $router = array_shift($link);
            } else {
                $router = 'public';
            }

            $link = array_shift($link);

            return \XF::app()->router($router)->buildLink($link, $params, $extra);
        }
        return '';

    }

    public function runOnce($key, \Closure $fn)
    {
        if ($this->isXFInitialized()) {
            \XF::runOnce($key, $fn);
        }
    }

    public function runLater(\Closure $fn)
    {
        if ($this->isXFInitialized()) {
            \XF::runLater($fn);
        }
    }

    public function visitor()
    {
        if ($this->isXFInitialized()) {
            return \XF::visitor();
        }
        return new \XLink\Proxy();
    }

    public function controller($class, $request)
    {
        return $this->xfApp()->controller($class, $request);
    }

    public function response()
    {
        return $this->xfApp()->response();
    }

    /**
     * @param $visitor
     * @param Closure $fn
     * @return mixed
     * @throws Exception
     */
    public function asVisitor($visitor, \Closure $fn)
    {
        if ($this->isXFInitialized()) {
            return \XF::asVisitor($visitor, $fn);
        }
        return new \XLink\Proxy();
    }

    public function style()
    {
        if ($this->isXFInitialized()) {
            return \XF::app()->style();
        }
        return new \XLink\Proxy();
    }

    public function simpleCache()
    {
        return $this->xfApp()->simpleCache();
    }

    public function language()
    {
        if ($this->isXFInitialized()) {
            return \XF::language();
        }
        return new \XLink\Proxy();
    }

    public function session()
    {
        if ($this->isXFInitialized()) {
            return \XF::session();
        }
        return new \XLink\Proxy();
    }

    public function mailer()
    {
        if ($this->isXFInitialized()) {
            return \XF::mailer();
        }
        return new \XLink\Proxy();
    }

    public function db()
    {
        if ($this->isXFInitialized()) {
            return \XF::db();
        }
        return new \XLink\Proxy();
    }

    public function permissionCache()
    {
        if ($this->isXFInitialized()) {
            return \XF::permissionCache();
        }
        return new \XLink\Proxy();
    }

    public function fs()
    {
        if ($this->isXFInitialized()) {
            return \XF::fs();
        }
        return new \XLink\Proxy();
    }

    /**
     * @param $class
     * @param null $fakeBaseClass
     * @return string|\XLink\Proxy
     * @throws Exception
     */
    public function extendClass($class, $fakeBaseClass = null)
    {
        if ($this->isXFInitialized()) {
            return \XF::extendClass($class, $fakeBaseClass);
        }
        return new \XLink\Proxy();
    }

    public function bbCode()
    {
        if ($this->isXFInitialized()) {
            return \XF::app()->bbCode();
        }
        return new \XLink\Proxy();
    }

    public function request()
    {
        if ($this->isXFInitialized()) {
            return \XF::app()->request();
        }
        return new \XLink\Proxy();
    }

    public function styleProperty($propertyId)
    {
        return $this->templater()->getStyle()->getProperty($propertyId);
    }

    public function ad($position, $arguments = [])
    {
        return $this->templater()->callAdsMacro($position, $arguments, []);
    }

    public function entityLink($type, $id)
    {
        return $this->em()->find('ThemeHouse\XLink:EntityLink', [
            $this->platformLink()->platform_id,
            $id,
            $type
        ]);
    }

    public function accountLink($remoteUser, $xenforoUser = null)
    {
        if ($xenforoUser) {
            return $this->finder('ThemeHouse\XLink:AccountLink')
                ->where('user_id', '=', $xenforoUser)
                ->where('platform_id', '=', $this->platformLink()->platform_id)
                ->fetchOne();
        }

        return $this->em()->find('ThemeHouse\XLink:AccountLink', [
            $this->platformLink()->platform_id,
            $remoteUser
        ]);
    }
}