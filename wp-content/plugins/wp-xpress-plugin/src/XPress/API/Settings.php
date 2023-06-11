<?php

namespace XPress\API;

class Settings extends AbstractEndpoint
{
    protected function register()
    {
        register_rest_route('xpress/v1', '/settings', [
            [
                'methods' => \WP_REST_Server::READABLE,
                'callback' => [$this, 'get_item'],
                'args' => [],
                'permission_callback' => [$this, 'get_item_permissions_check'],
            ],
            [
                'methods' => \WP_REST_Server::EDITABLE,
                'callback' => [$this, 'update_item'],
                'args' => $this->get_endpoint_args_for_item_schema(\WP_REST_Server::EDITABLE),
                'permission_callback' => [$this, 'get_item_permissions_check'],
            ],
            'schema' => array($this, 'get_public_item_schema'),
        ]);


        register_setting('xpress', 'xpress_xfpath', array('type' => 'string'));
        register_setting('xpress', 'xpress_xfurl', array('type' => 'string'));
    }

    public function get_item_permissions_check($request)
    {
        return current_user_can('administrator');
    }

    public function update_item($request)
    {
        $options = $this->get_registered_options();

        $params = $request->get_params();

        foreach ($options as $name => $args) {
            if (!array_key_exists($name, $params)) {
                continue;
            }

            if (!$args) {
                delete_option($args['option_name']);
            } else {
                update_option($args['option_name'], $request[$name]);
            }
        }

        return $this->get_item($request);
    }

    public function get_item($request)
    {
        $options = $this->get_registered_options();
        $response = array();

        foreach ($options as $name => $args) {
            $response[$name] = apply_filters('rest_pre_get_setting', null, $name, $args);

            if (is_null($response[$name])) {
                $response[$name] = get_option($args['option_name'], $args['schema']['default']);
            }

            $response[$name] = $this->prepare_value($response[$name], $args['schema']);
        }

        return $response;
    }

    protected function prepare_value($value, $schema)
    {
        if (is_wp_error(rest_validate_value_from_schema($value, $schema))) {
            return null;
        }
        return rest_sanitize_value_from_schema($value, $schema);
    }

    protected function get_registered_options()
    {
        return [
            'xpress_xfpath' => [
                'name' => 'XenForo Path',
                'schema' => [
                    'type' => 'string',
                    'description' => 'Local path to XenForo',
                    'default' => ''
                ],
                'option_name' => 'xpress_xfpath'
            ],
            'xpress_xfurl' => [
                'name' => 'XenForo URL',
                'schema' => [
                    'type' => 'string',
                    'description' => 'XenForo URL',
                    'default' => ''
                ],
                'option_name' => 'xpress_xfurl'
            ]
        ];
    }

    protected function set_additional_properties_to_false($schema)
    {
        switch ($schema['type']) {
            case 'object':
                foreach ($schema['properties'] as $key => $child_schema) {
                    $schema['properties'][$key] = $this->set_additional_properties_to_false($child_schema);
                }
                $schema['additionalProperties'] = false;
                break;
            case 'array':
                $schema['items'] = $this->set_additional_properties_to_false($schema['items']);
                break;
        }

        return $schema;
    }

    public function get_endpoint_args_for_item_schema($method = \WP_REST_Server::CREATABLE)
    {
        $schema = $this->get_item_schema();
        $schema_properties = !empty($schema['properties']) ? $schema['properties'] : array();
        $endpoint_args = array();

        foreach ($schema_properties as $field_id => $params) {

            // Arguments specified as `readonly` are not allowed to be set.
            if (!empty($params['readonly'])) {
                continue;
            }

            $endpoint_args[$field_id] = array(
                'validate_callback' => 'rest_validate_request_arg',
                'sanitize_callback' => 'rest_sanitize_request_arg',
            );

            if (isset($params['description'])) {
                $endpoint_args[$field_id]['description'] = $params['description'];
            }

            if (\WP_REST_Server::CREATABLE === $method && isset($params['default'])) {
                $endpoint_args[$field_id]['default'] = $params['default'];
            }

            if (\WP_REST_Server::CREATABLE === $method && !empty($params['required'])) {
                $endpoint_args[$field_id]['required'] = true;
            }

            foreach (array('type', 'format', 'enum', 'items', 'properties', 'additionalProperties') as $schema_prop) {
                if (isset($params[$schema_prop])) {
                    $endpoint_args[$field_id][$schema_prop] = $params[$schema_prop];
                }
            }

            // Merge in any options provided by the schema property.
            if (isset($params['arg_options'])) {

                // Only use required / default from arg_options on CREATABLE endpoints.
                if (\WP_REST_Server::CREATABLE !== $method) {
                    $params['arg_options'] = array_diff_key($params['arg_options'],
                        array('required' => '', 'default' => ''));
                }

                $endpoint_args[$field_id] = array_merge($endpoint_args[$field_id], $params['arg_options']);
            }
        }

        return $endpoint_args;
    }

    public function sanitize_callback($value, $request, $param)
    {
        if (is_null($value)) {
            return $value;
        }
        return rest_parse_request_arg($value, $request, $param);
    }

    public function get_item_schema()
    {
        $options = $this->get_registered_options();

        $schema = array(
            '$schema' => 'http://json-schema.org/draft-04/schema#',
            'title' => 'settings',
            'type' => 'object',
            'properties' => array(),
        );

        foreach ($options as $option_name => $option) {
            $schema['properties'][$option_name] = $option['schema'];
            $schema['properties'][$option_name]['arg_options'] = array(
                'sanitize_callback' => array($this, 'sanitize_callback'),
            );
        }

        return $this->add_additional_fields_schema($schema);
    }
}