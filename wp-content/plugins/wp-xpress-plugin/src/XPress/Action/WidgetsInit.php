<?php

namespace XPress\Action;

/**
 * Class Init
 * @package XPress\Action
 */
/**
 * Class WidgetsInit
 * @package XPress\Action
 */
class WidgetsInit
{
    /**
     *
     */
    public static function register()
    {
        /** @var \XF\Repository\Widget $widgetRepo */
        $widgetRepo = \XPress::xlink()->repository('XF:Widget');
        $widgets = $widgetRepo->getWidgetCache();
        $widgets = !empty($widgets['thxpress_wp_config']) ? $widgets['thxpress_wp_config'] : [];

        if (!empty($widgets)) {
            \XPress::setWidgets($widgets);
            if (count($widgets) && class_exists('\XPress\Widget\Widget')) {
                add_action('widgets_init', [self::class, 'registerXPressWidget'], 0);
            }

        }

        add_action('widgets_init', [self::class, 'actionWidgetsInit'], 0);
    }

    /**
     *
     */
    public static function registerXPressWidget()
    {
        register_widget('\XPress\Widget\Widget');
    }

    /**
     *
     */
    public static function actionWidgetsInit()
    {
        register_sidebar(array(
            'name' => \XPress::xlink()->phrase('thxpress_xenforo_configurations_sidebar'),
            'id' => 'xenforo',
            'description' => \XPress::xlink()->phrase('thxpress_xenforo_configurations_sidebar_desc'),
            'before_widget' => '<div id="%1$s" class="block block-xpress %2$s"><div class="block-container"><div class="block-row">',
            'after_widget' => '</div></div></div>',
            'before_title' => '</div><h3 class="block-minorHeader">',
            'after_title' => '</h3><div class="block-row">',
        ));
    }
}