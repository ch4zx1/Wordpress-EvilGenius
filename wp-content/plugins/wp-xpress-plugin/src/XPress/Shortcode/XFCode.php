<?php

namespace XPress\Shortcode;

/**
 * Class XFCode
 * @package XPress\Shortcode
 */
class XFCode
{
    /**
     *
     */
    public static function register()
    {
        add_shortcode('xfcpde', [self::class, 'shortcodeXFCode']);
    }

    /**
     * @param $atts
     * @param $content
     * @return string
     */
    public static function shortcodeXFCode($atts, $content)
    {
        return \XPress::xlink()->templater()->renderTemplate('public:bb_code_tag_code', [
            'content' => $content,
            'config' => [
                'phrase' => isset($atts['phrase']) ? $atts['phrase'] : null
            ],
            'language' => isset($atts['language']) ? $atts['language'] : null
        ]);
    }
}