<?php

namespace XPress\Filter;

/**
 * Class CommentsTemplate
 * @package XPress\Filter
 */
class CommentsTemplate
{
    /**
     *
     */
    public static function register()
    {
        add_filter('comments_template', [self::class, 'filterCommentsTemplate'], 0, 0);
    }

    /**
     * @return mixed|string
     */
    public static function filterCommentsTemplate()
    {
        global $post;

        $platformLink = \XPress::xlink()->platformLink();
        $options = $platformLink->options;

        if (!isset($options['comment_view']) || $options['comment_view'] !== 'xfthread') {
            return null;
        }

        $thread = \XPress::getThread($post);
        if (!$thread) {
            return null;
        }

        return THXPRESS_PLUGIN_DIR . '/src/templates/comments.php';
    }
}