<?php

namespace XPress\Action;

/**
 * Class DeletedPost
 * @package XPress\Action
 */
class DeletedPost
{
    /**
     *
     */
    public static function register()
    {
        add_action('deleted_post', [self::class, 'actionDeletedPost']);
    }

    /**
     * @param $comment
     * @throws \XF\PrintableException
     */
    public static function actionDeletedPost($comment) {
        if (!empty($comment->comment_ID)) {
            /** @var \ThemeHouse\XLink\Entity\EntityLink $postLink */
            $postLink = \XPress::getPostLink($comment);

            if (!$postLink) {
                return;
            }

            /** @var \XF\Entity\Post $post */
            $post = $postLink->XFEntity;
            $post->Thread->postRemoved($post);
            $post->delete(false);

            $postLink->delete(false);
        }
    }
}