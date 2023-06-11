<?php

namespace XPress\Filter;

/**
 * Class TheContent
 * @package XPress\Filter
 */
class TheContent
{
    /**
     *
     */
    public static function register()
    {
        add_filter('the_content', [self::class, 'filterTheContent'], 1, 1);
    }

    /**
     * @param $content
     * @return mixed|string
     */
    public static function filterTheContent($content)
    {
        if (is_admin()) {
            return $content;
        }

        $tags = wp_get_post_terms(get_the_ID());

        if (!is_array($tags)) {
            return $content;
        }

        $thread = \XPress::getThread(get_post());

        foreach ($tags as $tag) {
            if ($tag->slug == 'render-bb-code') {
                return preg_replace('/\n|\t/', '',
                    \XPress::xlink()->bbCode()->render(strip_tags($content), 'html', 'thxpress_post',
                        $thread ? $thread->FirstPost : null, []));
            }
        }

        return $content;
    }
}