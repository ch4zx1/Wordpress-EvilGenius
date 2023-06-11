<?php

namespace XLink;

class Registry
{
    /** @var App */
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function set($key, $value)
    {
        if ($this->app->isXFInitialized()) {
            \ThemeHouse\XLink\Registry::set($key, $value);
        }
    }

    public function get($key)
    {
        if ($this->app->isXFInitialized()) {
            return \ThemeHouse\XLink\Registry::get($key);
        }

        return null;
    }

    public function bulkSet(array $values)
    {
        if ($this->app->isXFInitialized()) {
            foreach ($values as $key => $value) {
                if (is_array($value)) {
                    $oldValue = \ThemeHouse\XLink\Registry::get($key);
                    if (is_array($oldValue)) {
                        $value = array_merge($oldValue, $value);
                    }
                }
                \ThemeHouse\XLink\Registry::set($key, $value);
            }
        }
    }

    public function loadCSS($css)
    {
        $cssSet = $this->get('load.css');
        $cssSet[] = $css;
        $this->set('load.css', array_unique($cssSet));
    }

    public function loadJS($js)
    {
        $jsSet = $this->get('load.js');
        $jsSet[] = $js;
        $this->set('load.js', array_unique($jsSet));
    }
}
