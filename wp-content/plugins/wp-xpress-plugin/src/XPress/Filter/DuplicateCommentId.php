<?php

namespace XPress\Filter;

/**
 * Class DuplicateCommentId
 * @package XPress\Filter
 */
class DuplicateCommentId
{
    /**
     *
     */
    public static function register()
    {
        add_filter('duplicate_comment_id', [self::class, 'filterDuplicateCommentId'], 1, 2);
    }

    /**
     * @param integer $commentId
     * @param array $commentData
     * @return integer|null
     */
    public static function filterDuplicateCommentId($commentId, $commentData)
    {
        $comment = get_comment($commentId);
        $xfPost = \XPress::getPost($comment);

        if ($xfPost) {
            return null;
        }

        return $commentId;
    }
}