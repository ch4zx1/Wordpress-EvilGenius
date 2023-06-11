<?php

namespace XPress\Filter;

/**
 * Class GetTheAuthorDescription
 * @package XPress\Filter
 */
class GetTheAuthorDescription
{
    /**
     *
     */
    public static function register()
    {
        $options = \XPress::xlink()->platformLink()->options;

        if (isset($options['about_sync']) && $options['about_sync']) {
            add_filter('get_the_author_description', [self::class, 'filterGetTheAuthorDescription'], 1, 2);
        }
    }

    /**
     * @param $value
     * @param $user_id
     * @return mixed|string
     */
    public static function filterGetTheAuthorDescription($value, $user_id)
    {
        $user = get_user_by('ID', $user_id);

        if (!$user instanceof \WP_User) {
            return null;
        }

        /** @var \XF\Entity\User $xfUser */
        $xfUser = \XPress::getXFUser($user);

        if ($xfUser) {
            return \XPress::xlink()->bbCode()->render($xfUser->Profile->about, 'html', 'thxpress_bio', null,
                []);
        }

        return $value;
    }
}