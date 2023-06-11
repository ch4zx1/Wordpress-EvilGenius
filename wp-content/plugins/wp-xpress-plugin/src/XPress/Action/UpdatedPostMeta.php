<?php

namespace XPress\Action;

/**
 * Class UpdatedPostMeta
 * @package XPress\Action
 */
class UpdatedPostMeta
{
    /**
     *
     */
    public static function register()
    {
        add_action('updated_post_meta', [self::class, 'actionUpdatePostMeta'], 10, 1);
    }

    /**
     * @param $postId
     */
    public static function actionUpdatePostMeta($postId)
    {
        $threadLink = \XPress::getThreadLink($postId);

        if (!$threadLink) {
            return;
        }

        /** @var \ThemeHouse\XPress\Repository\XPress $repository */
        $repository = \XPress::xlink()->repository('ThemeHouse\XPress:XPress');
        $repository->syncTags($threadLink, false, true);
    }
}