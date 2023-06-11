<?php

namespace XPress\Action;

/**
 * Class EditTerm
 * @package XPress\Action
 */
class EditTerm
{
    /**
     *
     */
    public static function register()
    {
        add_action('edit_term', [self::class, 'actionEditTerm'], 10, 3);
        add_action('create_term', [self::class, 'actionEditTerm'], 10, 3);
        add_action('delete_term', [self::class, 'actionEditTerm'], 10, 3);
    }

    /**
     * @param $postId
     * @param $post
     * @param $update
     */
    public static function actionEditTerm($postId, $post, $update) {
        $simpleCache = \XPress::xlink()->simpleCache();
        if ($simpleCache) {
            $categories = [];

            $wpCategories = get_categories(array(
                "hide_empty" => 0,
                "type" => "post",
                "orderby" => "name",
                "order" => "ASC"
            ));
            /** @noinspection PhpUndefinedMethodInspection */
            foreach ($wpCategories as $category) {
                $categories[$category->cat_ID] = [
                    'category_id' => $category->cat_ID,
                    'title' => $category->cat_name,
                    'slug' => $category->category_nicename,
                    'href' => get_category_link($category->cat_ID),
                    'description' => $category->category_description,
                    'parent_category_id' => $category->parent,
                    'article_count' => $category->category_count
                ];
            }
            $simpleCache->setValue('ThemeHouse/XPress', 'xpress.subnav.categories', $categories);
        }
    }

}