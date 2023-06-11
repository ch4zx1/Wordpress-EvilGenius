<?php

namespace XPress\Shortcode;

/**
 * Class ThxpressCommentUrl
 * @package XPress\Shortcode
 */
class ThxpressCommentUrl
{
    /**
     *
     */
    public static function register()
    {
        add_shortcode('thxpress_comment_url', [self::class, 'shortcodeThxpressCommentUrl']);
    }

    /**
     */
    public static function shortcodeThxpressCommentUrl()
    {
        $id = get_the_ID();
        if (!$id) {
            /** @noinspection PhpInconsistentReturnPointsInspection */
            return;
        }

        $data = \XPress::getThreadData($id);
        if (!$data) {
            return '#';
        }

        echo $data['comment_url'];
        return null;
    }
}