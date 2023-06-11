<?php

namespace XPress\Widget;

/**
 * Class Widget
 * @package XPress\Widget
 */
class Widget extends AbstractWidget
{

    /**
     * @param array $instance
     * @return string|void
     */
    public function form($instance)
    {
        $widgets = \XPress::getWidgets();
        $types = [['text' => '', 'value' => '']];
        foreach ($widgets as $widget) {
            $types[] = [
                'value' => $widget['widget_id'],
                'text' => $widget['widget_key']
            ];
        }

        $instance = wp_parse_args((array)$instance, ['title' => '', 'type' => false]);

        $fields = [
            'title' => [
                'type' => 'text',
                'title' => \XF::phrase('title:'),
                'value' => $instance['title']
            ],
            'type' => [
                'type' => 'select',
                'title' => \XF::phrase('thxpress_widget_type:'),
                'value' => $instance['type'],
                'values' => $types
            ]
        ];

        $this->renderOptions($fields);
    }

    /**
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        $widgets = \XPress::getWidgets();

        if (empty($widgets[$instance['type']])) {
            return;
        }

        $def = $widgets[$instance['type']];
        $widget = \XF::app()->widget()->getWidgetFilename($def);
        $tempFile = \XF\Util\File::copyAbstractedPathToTempFile('code-cache://widgets/' . $widget);
        $renderer = include($tempFile);
        echo $renderer(\XPress::xlink()->templater(), ['xf' => \XPress::xlink()->xfApp()->getGlobalTemplateData()],
            $def['options']);
        return;
    }
}
