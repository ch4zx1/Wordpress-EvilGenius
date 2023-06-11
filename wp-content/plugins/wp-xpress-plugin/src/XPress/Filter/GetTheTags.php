<?php

namespace XPress\Filter;

/**
 * Class GetTheTags
 * @package XPress\Filter
 */
class GetTheTags
{
    /**
     *
     */
    public static function register()
    {
        add_filter('get_the_tags', [self::class, 'filterGetTheTags']);
    }

    /**
     * @param $terms
     * @return mixed|string
     */
    public static function filterGetTheTags($terms) {
        if (!\XPress::getStyleProperty('thxpress_showFunctionalTags')) {
            /** @var \ThemeHouse\XPress\Repository\XPress $repo */
            $repo = \XPress::xlink()->repository('ThemeHouse\XPress:XPress');

            $functionalTags = $repo->getFunctionalTags();
            if (is_array($terms)) {
                $terms = array_filter($terms, function (\WP_Term $term) use ($functionalTags) {
                    /** @var \WP_Term $term */
                    return !in_array($term->slug, $functionalTags);
                });
            }
        }

        return $terms;
    }
}