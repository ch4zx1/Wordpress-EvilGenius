<?php

namespace XPress\Shortcode;

/**
 * Class XFQuote
 * @package XPress\Shortcode
 */
class XFQuote
{
    /**
     *
     */
    public static function register()
    {
        add_shortcode('xfquote', [self::class, 'shortcodeXFQuote']);
    }

    /**
     * @param $atts
     * @param $content
     * @return string
     */
    public static function shortcodeXFQuote($atts, $content)
    {
        return \XPress::xlink()->templater()->renderTemplate('public:bb_code_tag_quote', [
            'content' => $content,
            'name' => isset($atts['name']) ? $atts['name'] : null,
            'source' => isset($atts['id']) && isset($atts['type']) ? [
                'id' => $atts['id'],
                'type' => $atts['type']
            ] : null
        ]);
    }
}