<?php

namespace XPress\API;

class WidgetList extends AbstractEndpoint
{
    protected function register()
    {
        register_rest_route('xpress/v1', '/widget-list', [
            array(
                'methods' => \WP_REST_Server::READABLE,
                'callback' => array($this, 'get_item'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
                'args' => array(
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
        global $wp_registered_widgets;
        $wp_sidebar_widgets = wp_get_sidebars_widgets();

        $widgetList = isset($wp_sidebar_widgets['xenforo']) ? $wp_sidebar_widgets['xenforo'] : [];
        $widgets = array_intersect_key($wp_registered_widgets, array_flip($widgetList));


        $data = array_map(function($element) {
            return [
                'name' => $element['name'],
                'description' => $element['description'],
                'id' => $element['id']
            ];
        }, $widgets);

        $response = rest_ensure_response($data);

        return $response;
    }
}