<?php

namespace XPress\Action;

/**
 * Class DeleteUser
 * @package XPress\Action
 */
class DeleteUser
{
    /**
     *
     */
    public static function register()
    {
        add_action('delete_user', [self::class, 'actionDeleteUser']);
    }

    /**
     * @param $userId
     * @throws \XF\PrintableException
     */
    public static function actionDeleteUser($userId)
    {
        $link = \XPress::xlink()->accountLink($userId);
        if($link) {
            $link->delete(false);
        }
    }
}