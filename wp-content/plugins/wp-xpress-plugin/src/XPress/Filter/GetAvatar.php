<?php

namespace XPress\Filter;

/**
 * Class GetAvatar
 * @package XPress\Filter
 */
class GetAvatar
{
    /**
     *
     */
    public static function register()
    {
        $options = \XPress::xlink()->platformLink()->options;
        if (isset($options['avatar_sync']) && $options['avatar_sync']) {
            add_filter('get_avatar', [self::class, 'filterGetAvatar'], 1, 5);
        }
    }

    /**
     * @param $avatar
     * @param $id_or_email
     * @param $size
     * @param $default
     * @param $alt
     * @return mixed|string
     */
    public static function filterGetAvatar(
        $avatar,
        $id_or_email,
        $size,
        /** @noinspection PhpUnusedParameterInspection */
        $default,
        $alt
    ) {
        $user = false;

        if (is_numeric($id_or_email)) {
            $user = get_user_by('id', intval($id_or_email));
        } elseif (is_object($id_or_email)) {
            if (!empty($id_or_email->user_id)) {
                $user = get_user_by('id', intval($id_or_email->user_id));
            }
        } else {
            $user = get_user_by('email', $id_or_email);
        }

        if ($size <= 30) {
            $xfSize = 'xxs';
        } elseif ($size <= 42) {
            $xfSize = 'xs';
        } elseif ($size <= 72) {
            $xfSize = 's';
        } elseif ($size <= 144) {
            $xfSize = 'm';
        } elseif ($size <= 192) {
            $xfSize = 'l';
        } else {
            $xfSize = 'h';
        }

        if (is_object($user)) {
            $xfUser = \XPress::getXFUser($user);
            if ($xfUser) {
                $avatarUrl = $xfUser->getAvatarUrl($xfSize);
            }

            if (isset($avatarUrl) && $avatarUrl) {
                return "<img alt='{$alt}' src='{$avatarUrl}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
            } else {
                $outerAttributes = ['style' => null, 'class' => null];
                /** @noinspection PhpUndefinedMethodInspection */
                $innerHtml = \XPress::xlink()->templater()->getXPressDynamicAvatarHtml(isset($xfUser) ? $xfUser->username : $user->user_nicename,
                    null,
                    $outerAttributes);
                return "<span style='{$outerAttributes['style']}' class='{$outerAttributes['class']} avatar--{$xfSize} avatar'>{$innerHtml}</span>";
            }
        }

        global $comment;

        if ($comment) {
            $outerAttributes = ['style' => null, 'class' => null];
            /** @noinspection PhpUndefinedMethodInspection */
            $innerHtml = \XPress::xlink()->templater()->getXPressDynamicAvatarHtml($comment->comment_author,
                null,
                $outerAttributes);
            return "<span style='{$outerAttributes['style']}' class='{$outerAttributes['class']} avatar--{$xfSize} avatar'>{$innerHtml}</span>";
        }

        return $avatar;
    }
}