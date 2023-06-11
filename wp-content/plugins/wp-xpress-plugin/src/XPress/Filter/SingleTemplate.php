<?php

namespace XPress\Filter;

/**
 * Class SingleTemplate
 * @package XPress\Filter
 */
class SingleTemplate
{
    /**
     *
     */
    public static function register()
    {
        add_filter('single_template', [self::class, 'filterSingleTemplate']);
    }

    /**
     * @param $single_template
     * @return mixed|string
     */
    public static function filterSingleTemplate($single_template) {
        $post = get_post();
        $tags = wp_get_post_terms($post->ID);

        $isRedirect = false;
        foreach ($tags as $tag) {
            if ($tag->slug == 'redirect-xf') {
                $isRedirect = true;
            }
            if ($tag->slug == 'redirect-wp') {
                return $single_template;
            }
        }

        if ($isRedirect) {
            $thread = \XPress::getThread($post);

            if ($thread && $thread->canView()) {
                $link = \XPress::xlink()->buildLink('threads', $thread);
                wp_redirect($link);
                exit;
            }
        }

        return $single_template;
    }
}