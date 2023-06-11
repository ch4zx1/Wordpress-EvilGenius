<?php

namespace XPress\Action;

/**
 * Class EditedCategory
 * @package XPress\Action
 */
class EditedCategory
{
    /**
     *
     */
    public static function register()
    {
        add_action('edited_category', [self::class, 'actionEditedCategory'], 9, 2);
    }

    /**
     * @param $term_id
     * @throws \XF\PrintableException
     */
    public static function actionEditedCategory($term_id)
    {
        if (isset($_POST['term_meta'])) {
            $term_meta = $_POST['term_meta'];

            $categoryLink = \XPress::xlink()->em()->find('ThemeHouse\XLink:EntityLink', [
                \XPress::xlink()->platformLink()->platform_id,
                $term_id,
                'category'
            ]);

            if (!$categoryLink) {
                $categoryLink = \XPress::xlink()->em()->create('ThemeHouse\XLink:EntityLink');
                $categoryLink->bulkSet([
                    'platform_id' => \XPress::xlink()->platformLink()->platform_id,
                    'remote_entity_id' => $term_id,
                    'remote_entity_type' => 'category',
                    'created_on' => 'remote'
                ]);
            }

            $categoryLink->bulkSet([
                'content_type' => 'XF:Node',
                'content_id' => isset($term_meta['_xPressXFForumID']) ? $term_meta['_xPressXFForumID'] : 0,
                'extra' => [
                    'category' => isset($term_meta['_xPressXFCategoryID']) ? $term_meta['_xPressXFCategoryID'] : 0,
                    'bg_color' => isset($term_meta['_xPressCatBackgroundColor']) ? $term_meta['_xPressCatBackgroundColor'] : '#000000',
                    'text_color' => isset($term_meta['_xPressCatTextColor']) ? $term_meta['_xPressCatTextColor'] : '#ffffff'
                ]
            ]);

            $categoryLink->save();
        }
    }

}