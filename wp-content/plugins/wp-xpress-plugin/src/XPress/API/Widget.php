<?php

namespace XPress\API;

class Widget extends AbstractEndpoint
{
    protected function register()
    {
        register_rest_route('xpress/v1', '/widget', [
            array(
                'methods' => \WP_REST_Server::READABLE,
                'callback' => array($this, 'get_item'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier'),
                        'type' => 'string',
                    ),
                    'context' => $this->get_context_param(array('default' => 'view')),
                ),
            ),
        ]);
    }

    public function get_item_permissions_check($request)
    {
        return current_user_can('administrator');
    }

    public function get_item($request)
    {
        add_filter('sidebars_widgets', function ($sidebars_widgets) use ($request) {
            $sidebars_widgets['xenforo'] = [$request['id']];
            return $sidebars_widgets;
        });

        ob_start();
        dynamic_sidebar('xenforo');
        $widget = ob_get_clean();

        $response = rest_ensure_response($widget);

        return $response;
    }
}