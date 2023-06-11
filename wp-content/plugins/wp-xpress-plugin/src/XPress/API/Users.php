<?php

namespace XPress\API;

use XLink\App;

class Users extends AbstractEndpoint
{
    protected $meta;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->meta = new \WP_REST_User_Meta_Fields();
    }

    public function register()
    {
        register_rest_route('xpress/v1', '/users', [
                'args' => [],
                [
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => array($this, 'get_items'),
                    'permission_callback' => array($this, 'get_items_permissions_check'),
                    'args' => $this->get_collection_params(),
                ],
            ]
        );
    }

    public function get_items_permissions_check($request)
    {
        return current_user_can('administrator');
    }

    /**
     * Retrieves all users.
     *
     * @since 4.7.0
     *
     * @param \WP_REST_Request $request Full details about the request.
     * @return \WP_REST_Response|\WP_Error Response object on success, or WP_Error object on failure.
     */
    public function get_items($request)
    {

        // Retrieve the list of registered collection query parameters.
        $registered = $this->get_collection_params();

        /*
         * This array defines mappings between public API query parameters whose
         * values are accepted as-passed, and their internal WP_Query parameter
         * name equivalents (some are the same). Only values which are also
         * present in $registered will be set.
         */
        $parameter_mappings = array(
            'exclude' => 'exclude',
            'include' => 'include',
            'order' => 'order',
            'per_page' => 'number',
            'search' => 'search',
            'roles' => 'role__in',
            'slug' => 'nicename__in',
        );

        $prepared_args = array();

        /*
         * For each known parameter which is both registered and present in the request,
         * set the parameter's value on the query $prepared_args.
         */
        foreach ($parameter_mappings as $api_param => $wp_param) {
            if (isset($registered[$api_param], $request[$api_param])) {
                $prepared_args[$wp_param] = $request[$api_param];
            }
        }

        if (isset($registered['offset']) && !empty($request['offset'])) {
            $prepared_args['offset'] = $request['offset'];
        } else {
            $prepared_args['offset'] = ($request['page'] - 1) * $prepared_args['number'];
        }

        if (isset($registered['orderby'])) {
            $orderby_possibles = array(
                'id' => 'ID',
                'include' => 'include',
                'name' => 'display_name',
                'registered_date' => 'registered',
                'slug' => 'user_nicename',
                'include_slugs' => 'nicename__in',
                'email' => 'user_email',
                'url' => 'user_url',
            );
            $prepared_args['orderby'] = $orderby_possibles[$request['orderby']];
        }

        if (isset($registered['who']) && !empty($request['who']) && 'authors' === $request['who']) {
            $prepared_args['who'] = 'authors';
        } elseif (!current_user_can('list_users')) {
            $prepared_args['has_published_posts'] = get_post_types(array('show_in_rest' => true), 'names');
        }

        if (!empty($prepared_args['search'])) {
            $prepared_args['search'] = '*' . $prepared_args['search'] . '*';
        }
        /**
         * Filters WP_User_Query arguments when querying users via the REST API.
         *
         * @link https://developer.wordpress.org/reference/classes/wp_user_query/
         *
         * @since 4.7.0
         *
         * @param array $prepared_args Array of arguments for WP_User_Query.
         * @param \WP_REST_Request $request The current request.
         */
        $prepared_args = apply_filters('rest_user_query', $prepared_args, $request);

        $query = new \WP_User_Query($prepared_args);

        $users = array();

        foreach ($query->results as $user) {
            $data = $this->prepare_item_for_response($user, $request);
            $users[] = $this->prepare_response_for_collection($data);
        }

        $response = rest_ensure_response($users);

        // Store pagination values for headers then unset for count query.
        $per_page = (int)$prepared_args['number'];
        $page = ceil((((int)$prepared_args['offset']) / $per_page) + 1);

        $prepared_args['fields'] = 'ID';

        $total_users = $query->get_total();

        if ($total_users < 1) {
            // Out-of-bounds, run the query again without LIMIT for total count.
            unset($prepared_args['number'], $prepared_args['offset']);
            $count_query = new \WP_User_Query($prepared_args);
            $total_users = $count_query->get_total();
        }

        $response->header('X-WP-Total', (int)$total_users);

        $max_pages = ceil($total_users / $per_page);

        $response->header('X-WP-TotalPages', (int)$max_pages);

        $base = add_query_arg($request->get_query_params(),
            rest_url(sprintf('%s/%s', $this->namespace, $this->rest_base)));
        if ($page > 1) {
            $prev_page = $page - 1;

            if ($prev_page > $max_pages) {
                $prev_page = $max_pages;
            }

            $prev_link = add_query_arg('page', $prev_page, $base);
            $response->link_header('prev', $prev_link);
        }
        if ($max_pages > $page) {
            $next_page = $page + 1;
            $next_link = add_query_arg('page', $next_page, $base);

            $response->link_header('next', $next_link);
        }

        return $response;
    }

    /**
     * Prepares a single user output for response.
     *
     * @since 4.7.0
     *
     * @param \WP_User $user User object.
     * @param \WP_REST_Request $request Request object.
     * @return \WP_REST_Response Response object.
     */
    public function prepare_item_for_response($user, $request)
    {

        $data = array();
        $fields = $this->get_fields_for_response($request);

        if (in_array('id', $fields, true)) {
            $data['remote_user_id'] = $user->ID;
        }

        if (in_array('username', $fields, true)) {
            $data['username'] = $user->user_login;
        }

        if (in_array('email', $fields, true)) {
            $data['email'] = $user->user_email;
        }

        if (in_array('description', $fields, true)) {
            $data['about'] = $user->description;
        }

        if (in_array('password', $fields, true)) {
            // Defensively call array_values() to ensure an array is returned.
            $data['password_data'] = ['hash' => $user->user_pass];
            $data['password_scheme'] = 'ThemeHouse\XPress:WordPress';
        }

        if (in_array('registered_date', $fields, true)) {
            $data['register_date'] = strtotime($user->user_registered);
        }

        if (in_array('avatar_urls', $fields, true)) {
            $avatar_files = rest_get_avatar_urls($user->user_email);
            $data['avatar_file'] = $avatar_files[96];
        }

        $context = !empty($request['context']) ? $request['context'] : 'embed';

        $data = $this->add_additional_fields_to_object($data, $request);
        $data = $this->filter_response_by_context($data, $context);

        // Wrap the data in a response object.
        $response = rest_ensure_response($data);

        /**
         * Filters user data returned from the REST API.
         *
         * @since 4.7.0
         *
         * @param \WP_REST_Response $response The response object.
         * @param object $user User object used to create response.
         * @param \WP_REST_Request $request Request object.
         */
        return apply_filters('rest_prepare_user', $response, $user, $request);
    }

    /**
     * Retrieves the user's schema, conforming to JSON Schema.
     *
     * @since 4.7.0
     *
     * @return array Item schema data.
     */
    public function get_item_schema()
    {
        $schema = array(
            '$schema' => 'http://json-schema.org/draft-04/schema#',
            'title' => 'user',
            'type' => 'object',
            'properties' => array(
                'id' => array(
                    'description' => __('Unique identifier for the user.'),
                    'type' => 'integer',
                    'context' => array('embed', 'view', 'edit'),
                    'readonly' => true,
                ),
                'username' => array(
                    'description' => __('Login name for the user.'),
                    'type' => 'string',
                    'context' => array('edit'),
                    'required' => true,
                    'arg_options' => array(
                        'sanitize_callback' => array($this, 'check_username'),
                    ),
                ),
                'name' => array(
                    'description' => __('Display name for the user.'),
                    'type' => 'string',
                    'context' => array('embed', 'view', 'edit'),
                    'arg_options' => array(
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                ),
                'first_name' => array(
                    'description' => __('First name for the user.'),
                    'type' => 'string',
                    'context' => array('edit'),
                    'arg_options' => array(
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                ),
                'last_name' => array(
                    'description' => __('Last name for the user.'),
                    'type' => 'string',
                    'context' => array('edit'),
                    'arg_options' => array(
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                ),
                'email' => array(
                    'description' => __('The email address for the user.'),
                    'type' => 'string',
                    'format' => 'email',
                    'context' => array('edit'),
                    'required' => true,
                ),
                'url' => array(
                    'description' => __('URL of the user.'),
                    'type' => 'string',
                    'format' => 'uri',
                    'context' => array('embed', 'view', 'edit'),
                ),
                'description' => array(
                    'description' => __('Description of the user.'),
                    'type' => 'string',
                    'context' => array('embed', 'view', 'edit'),
                ),
                'link' => array(
                    'description' => __('Author URL of the user.'),
                    'type' => 'string',
                    'format' => 'uri',
                    'context' => array('embed', 'view', 'edit'),
                    'readonly' => true,
                ),
                'locale' => array(
                    'description' => __('Locale for the user.'),
                    'type' => 'string',
                    'enum' => array_merge(array('', 'en_US'), get_available_languages()),
                    'context' => array('edit'),
                ),
                'nickname' => array(
                    'description' => __('The nickname for the user.'),
                    'type' => 'string',
                    'context' => array('edit'),
                    'arg_options' => array(
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                ),
                'slug' => array(
                    'description' => __('An alphanumeric identifier for the user.'),
                    'type' => 'string',
                    'context' => array('embed', 'view', 'edit'),
                    'arg_options' => array(
                        'sanitize_callback' => array($this, 'sanitize_slug'),
                    ),
                ),
                'registered_date' => array(
                    'description' => __('Registration date for the user.'),
                    'type' => 'string',
                    'format' => 'date-time',
                    'context' => array('edit'),
                    'readonly' => true,
                ),
                'roles' => array(
                    'description' => __('Roles assigned to the user.'),
                    'type' => 'array',
                    'items' => array(
                        'type' => 'string',
                    ),
                    'context' => array('edit'),
                ),
                'password' => array(
                    'description' => __('Password for the user.'),
                    'type' => 'string',
                    'context' => array('edit')
                ),
                'capabilities' => array(
                    'description' => __('All capabilities assigned to the user.'),
                    'type' => 'object',
                    'context' => array('edit'),
                    'readonly' => true,
                ),
                'extra_capabilities' => array(
                    'description' => __('Any extra capabilities assigned to the user.'),
                    'type' => 'object',
                    'context' => array('edit'),
                    'readonly' => true,
                ),
            ),
        );

        if (get_option('show_avatars')) {
            $avatar_properties = array();

            $avatar_sizes = rest_get_avatar_sizes();

            foreach ($avatar_sizes as $size) {
                $avatar_properties[$size] = array(
                    /* translators: %d: avatar image size in pixels */
                    'description' => sprintf(__('Avatar URL with image size of %d pixels.'), $size),
                    'type' => 'string',
                    'format' => 'uri',
                    'context' => array('embed', 'view', 'edit'),
                );
            }

            $schema['properties']['avatar_urls'] = array(
                'description' => __('Avatar URLs for the user.'),
                'type' => 'object',
                'context' => array('embed', 'view', 'edit'),
                'readonly' => true,
                'properties' => $avatar_properties,
            );
        }

        return $schema;
    }

    /**
     * Retrieves the query params for collections.
     *
     * @since 4.7.0
     *
     * @return array Collection parameters.
     */
    public function get_collection_params()
    {
        $query_params = parent::get_collection_params();

        $query_params['context']['default'] = 'view';

        $query_params['exclude'] = array(
            'description' => __('Ensure result set excludes specific IDs.'),
            'type' => 'array',
            'items' => array(
                'type' => 'integer',
            ),
            'default' => array(),
        );

        $query_params['include'] = array(
            'description' => __('Limit result set to specific IDs.'),
            'type' => 'array',
            'items' => array(
                'type' => 'integer',
            ),
            'default' => array(),
        );

        $query_params['offset'] = array(
            'description' => __('Offset the result set by a specific number of items.'),
            'type' => 'integer',
        );

        $query_params['order'] = array(
            'default' => 'asc',
            'description' => __('Order sort attribute ascending or descending.'),
            'enum' => array('asc', 'desc'),
            'type' => 'string',
        );

        $query_params['orderby'] = array(
            'default' => 'name',
            'description' => __('Sort collection by object attribute.'),
            'enum' => array(
                'id',
                'include',
                'name',
                'registered_date',
                'slug',
                'include_slugs',
                'email',
                'url',
            ),
            'type' => 'string',
        );

        $query_params['slug'] = array(
            'description' => __('Limit result set to users with one or more specific slugs.'),
            'type' => 'array',
            'items' => array(
                'type' => 'string',
            ),
        );

        $query_params['roles'] = array(
            'description' => __('Limit result set to users matching at least one specific role provided. Accepts csv list or single role.'),
            'type' => 'array',
            'items' => array(
                'type' => 'string',
            ),
        );

        $query_params['who'] = array(
            'description' => __('Limit result set to users who are considered authors.'),
            'type' => 'string',
            'enum' => array(
                'authors',
            ),
        );

        /**
         * Filter collection parameters for the users controller.
         *
         * This filter registers the collection parameter, but does not map the
         * collection parameter to an internal WP_User_Query parameter.  Use the
         * `rest_user_query` filter to set WP_User_Query arguments.
         *
         * @since 4.7.0
         *
         * @param array $query_params JSON Schema-formatted collection parameters.
         */
        return apply_filters('rest_user_collection_params', $query_params);
    }
}
