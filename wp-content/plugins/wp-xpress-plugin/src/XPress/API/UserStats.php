<?php

namespace XPress\API;

class UserStats extends AbstractEndpoint
{
    protected function register()
    {
        register_rest_route('xpress/v1', '/user-statistics', [
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

        global $wpdb;

        $userStats = [
            'posts' => count_user_posts($user->ID),
            'comments' => $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) AS total FROM $wpdb->comments WHERE comment_approved = 1 AND user_id = %s",
                $user->ID))
        ];

        $response = rest_ensure_response($userStats);

        return $response;
    }

    protected function get_user($id)
    {
        $error = new \WP_Error('rest_user_invalid_id', __('Invalid user ID.'), array('status' => 404));
        if ((int)$id <= 0) {
            return $error;
        }

        $user = get_userdata((int)$id);
        if (empty($user) || !$user->exists()) {
            return $error;
        }

        if (is_multisite() && !is_user_member_of_blog($user->ID)) {
            return $error;
        }

        return $user;
    }
}