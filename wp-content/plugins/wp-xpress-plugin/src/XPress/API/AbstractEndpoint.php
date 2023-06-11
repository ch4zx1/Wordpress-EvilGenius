<?php

namespace XPress\API;

use XLink\App;

abstract class AbstractEndpoint extends \WP_REST_Controller {
    /** @var App */
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->register();
    }

    protected abstract function register();
}