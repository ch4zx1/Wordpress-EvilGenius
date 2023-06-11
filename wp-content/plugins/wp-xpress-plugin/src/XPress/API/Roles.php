<?php

namespace XPress\API;

class Roles extends AbstractEndpoint
{
    protected function register()
    {
        register_rest_route('xpress/v1', '/roles', [
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
        global $wp_roles;

        if ( ! isset( $wp_roles ) )
            $wp_roles = new \WP_Roles();

        $editable_roles = apply_filters('editable_roles', $wp_roles);

        return $editable_roles->get_names();
    }
}