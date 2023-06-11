<?php

namespace XPress\Shortcode;

/**
 * Class XFAttach
 * @package XPress\Shortcode
 */
class XFAttach
{
    /**
     *
     */
    public static function register()
    {
        add_shortcode('xfattach', [self::class, 'shortcodeXFAttach']);
    }

    /**
     * @param $atts
     * @param $content
     * @return string
     */
    public static function shortcodeXFAttach($atts, $content)
    {
        $attachment = \XPress::xlink()->em()->find('XF:Attachment', $content);
        return \XPress::xlink()->templater()->renderTemplate('public:bb_code_tag_attach', [
            'id' => $content,
            'attachment' => $attachment,
            'canView' => true,
            'full' => isset($atts['full']) ? $atts['full'] : false
        ]);
    }
}