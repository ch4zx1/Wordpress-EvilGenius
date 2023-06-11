<?php

namespace XPress\Shortcode;

/**
 * Class XFSpoiler
 * @package XPress\Shortcode
 */
class XFSpoiler
{
    /**
     *
     */
    public static function register()
    {
        add_shortcode('xfspoiler', [self::class, 'shortcodeXFSpoiler']);
    }

    /**
     * @param $atts
     * @param $content
     * @return string
     */
    public static function shortcodeXFSpoiler($atts, $content)
    {
        return \XPress::xlink()->templater()->renderTemplate('public:bb_code_tag_spoiler', [
            'content' => $content,
            'title' => isset($atts['title']) ? $atts['title'] : null
        ]);
    }
}