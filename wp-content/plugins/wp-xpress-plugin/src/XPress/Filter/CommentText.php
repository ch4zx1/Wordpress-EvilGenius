<?php

namespace XPress\Filter;

/**
 * Class CommentText
 * @package XPress\Filter
 */
class CommentText
{
    /**
     *
     */
    public static function register()
    {
        add_filter('comment_text', [self::class, 'filterCommentText'], 1, 2);
    }

    /**
     * @param string $text
     * @param null $comment
     * @return mixed|string
     */
    public static function filterCommentText($text = '', $comment = null)
    {
        $post = \XPress::getPostLink($comment);

        if ($post) {
            return \XPress::xlink()->bbCode()->render($text, 'html', 'thxpress_comment', null, []);
        }

        return $text;
    }
}