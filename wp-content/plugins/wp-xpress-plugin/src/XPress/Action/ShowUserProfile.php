<?php

namespace XPress\Action;

/**
 * Class ShowUserProfile
 * @package XPress\Action
 */
class ShowUserProfile
{
    /**
     *
     */
    public static function register()
    {
        add_action('show_user_profile', [self::class, 'actionShowUserProfile']);
    }

    /**
     * @param $user
     */
    public static function actionShowUserProfilefunction($user)
    {
        echo '<h3>XenForo</h3>';

        $user = \XPress::getXFUser($user);
        echo '
            <table class="form-table">
                <tr>
                    <th>' . \XPress::xlink()->phrase('thxpress_linked_xf_account') . '</th>
                    <td>' . ($user ? '<a href="' . \XPress::xlink()->buildLink('members',
                    $user) . '" target="_blank">' . $user->username . '</a>' : \XPress::xlink()->phrase('none')) . '</td>
                </tr>
            </table>
            ';
    }
}