<?php

namespace XPress\Action;

/**
 * Class PostUpdated
 * @package XPress\Action
 */
class PostUpdated
{
    /**
     *
     */
    public static function register()
    {
        add_action('post_updated', [self::class, 'actionPostUpdated'], 10, 3);
    }

    /**
     * @param $postId
     * @param $post_after
     * @param $post_before
     */
    public static function actionPostUpdated($postId, $post_after, $post_before)
    {
        if (!in_array(get_post_type($postId),
                \XPress::getCompatiblePostTypes()) || $post_after->post_status != 'publish') {
            return;
        }

        if ($post_after->post_author != $post_before->post_author) {
            $threadLink = \XPress::getThreadLink($post_before);
            $threadLink->user_id = \XPress::getXFUser(get_user_by('ID', $post_after->post_author));
            $threadLink->saveIfChanged();
        }
    }
}