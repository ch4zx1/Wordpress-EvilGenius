<?php

namespace XPress\Widget;

class AbstractWidget extends \WP_Widget {
    public function __construct($desc = null, $id = null, $name = null) {
        $widget_options = [
            'description' => $desc ?: \XF::phrase('thxpress_wp_widget_desc'),
            'classname' => 'xpress'
        ];

        parent::__construct($id ?: 'xpress', $name ?: \XF::phrase('thxpress')->render(), $widget_options, []);
    }
    
    protected function renderOptions($fields) {
        foreach($fields as $key => $field) {
            $id = $this->get_field_id($key);
            $name = $this->get_field_name($key);

            switch($field['type']) {
                case 'text':
                    echo "<label for='{$id}'>{$field['title']}";

                    echo "<input class='widefat' id='{$id}' name='{$name}' type='text' value='{$field['value']}' />";

                    echo "</label>";
                    break;

                case 'select':
                    echo "<label for='{$id}'>{$field['title']}";

                    echo "<select class='widefat' id='{$id}' name='{$name}' type='text'>";

                    foreach($field['values'] as $value) {
                        $selected = $field['value'] == $value['value'] ? 'selected' : '';

                        echo "<option value='{$value['value']}' {$selected}>{$value['text']}</option>";
                    }

                    echo "</select>";

                    echo "</label>";
                    break;

                default:
                    break;
            }
        }
    }
}