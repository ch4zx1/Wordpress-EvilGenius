<?php

namespace XPress\Shortcode;

/**
 * Class ThxpressCommentCount
 * @package XPress\Shortcode
 */
class ThxpressCommentCount
{
    /**
     *
     */
    public static function register()
    {
        add_shortcode('thxpress_comment_count', [self::class, 'shortcodeThxpressCommentCount']);
    }

    /**
     */
    public static function shortcodeThxpressCommentCount()
    {
        $id = get_the_ID();
        if (!$id) {
            return;
        }

        $data = \XPress::getThreadData($id);
        if (!$data) {
            return;
        }

        echo $data['reply_count'];
    }
}