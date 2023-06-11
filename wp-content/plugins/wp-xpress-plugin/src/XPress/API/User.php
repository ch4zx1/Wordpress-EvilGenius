<?php

namespace XPress\API;

use XLink\App;

class User extends AbstractEndpoint
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->meta = new \WP_REST_User_Meta_Fields();
    }

    protected function register()
    {
        register_rest_route('xpress/v1', '/user', [
            'args' => array(
                'id' => array(
                    'description' => __('Unique identifier for the user.'),
                    'type' => 'integer',
                ),
            ),
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
        $user = $this->get_user($request['id']);
        if (is_wp_error($user)) {
            return $user;
        }

        $user = $this->prepare_item_for_response($user, $request);
        $response = rest_ensure_response($user);

        return $response;
    }

    protected function get_user($id)
    {
        $error = new \WP_Error('rest_user_invalid_id', __('Invalid user ID.'), array('status' => 404));
        if ((int)$id <= 0) {
            return $error;
        }

        $user = get_users([
            'meta_key' => '_xPressXFUserID',
            'meta_value' => $id
        ]);

        return array_shift($user);
    }

    public function prepare_item_for_response($user, $request)
    {
        return $user ? $user->ID : 0;
    }

    protected function prepare_links($user)
    {
        $links = array(
            'self' => array(
                'href' => rest_url(sprintf('%s/%s/%d', $this->namespace, $this->rest_base, $user->ID)),
            ),
            'collection' => array(
                'href' => rest_url(sprintf('%s/%s', $this->namespace, $this->rest_base)),
            ),
        );

        return $links;
    }
}