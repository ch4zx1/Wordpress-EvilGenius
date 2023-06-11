<?php

namespace XPress\Action;

/**
 * Class AddMetaBoxes
 * @package XPress\Action
 */
class AdminMenu
{
    /**
     *
     */
    public static function register()
    {
        add_action('admin_menu', [self::class, 'actionAdminMenu']);
    }

    /**
     */
    public static function actionAdminMenu()
    {
        if (current_user_can('manage_options')) {
            add_menu_page('XPress', 'XPress', 'manage_options', 'thxpress', function () {
            }, 'X', 59);

            global $submenu;
            $submenu['thxpress'] = [
                [
                    \XPress::xlink()->phrase('thxpress_wpnav_xenforo_admin_dashboard'),
                    'manage_options',
                    \XPress::xlink()->buildLink('admin:')
                ],
                [
                    \XPress::xlink()->phrase('thxpress_wpnav_settings'),
                    'manage_options',
                    \XPress::xlink()->buildLink('admin:xlink/platforms/edit', \XPress::xlink()->platformLink())
                ],
                [
                    \XPress::xlink()->phrase('thxpress_wpnav_linked_users'),
                    'manage_options',
                    \XPress::xlink()->buildLink('admin:xlink/platforms/users', \XPress::xlink()->platformLink())
                ],
                [
                    \XPress::xlink()->phrase('thxpress_wpnav_linked_entities'),
                    'manage_options',
                    \XPress::xlink()->buildLink('admin:xlink/platforms/entities',
                        \XPress::xlink()->platformLink())
                ],
                [
                    \XPress::xlink()->phrase('thxpress_wpnav_style_properties'),
                    'manage_options',
                    \XPress::xlink()->buildLink('admin:styles/style-properties/group',
                        ['style_id' => \XPress::xlink()->style()->getId()], ['group' => 'thxpress'])
                ]
            ];
        }
    }

}