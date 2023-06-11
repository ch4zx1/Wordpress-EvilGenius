<?php

namespace XPress\Action;

/**
 * Class BeforePostDelete
 * @package XPress\Action
 */
class BeforePostDelete
{
    /**
     *
     */
    public static function register()
    {
        add_action('before_post_delete', [self::class, 'actionBeforePostDelete']);
    }

    /**
     * @param $postId
     * @throws \XF\PrintableException
     */
    public static function actionBeforePostDelete($postId)
    {
        $threadLink = \XPress::getThreadLink($postId);

        if ($threadLink) {
            if ($threadLink->XFEntity) {
                $threadLink->XFEntity->fastUpdate('discussion_open', false);
            }

            $threadLink->delete(false);
        }
    }
}