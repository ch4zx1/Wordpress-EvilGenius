<?php

namespace XPress\Action;

use XF\Option\Forum;

/**
 * Class CategoryEditFormFields
 * @package XPress\Action
 */
class CategoryEditFormFields
{
    /**
     *
     */
    public static function register()
    {
        add_action('category_edit_form_fields', [self::class, 'actionCategoryEditFormFields']);
    }

    /**
     * @param $term
     */
    public static function actionCategoryEditFormFields($term) {
        /** @var \ThemeHouse\XLink\Entity\EntityLink $categoryLink */
        $categoryLink = \XPress::getCategoryLink($term->term_id);
        $term_meta = $categoryLink->extra;

        echo '<tr class="form-field"><th scope="row"><label for="term_meta[_xPressXFForumID]">' . \XPress::xlink()->phrase('thxpress_xf_forum_id') . '</label></th>
                <td class="thxpress-forum"><style>td.thxpress-forum dl, td.thxpress-forum dd {margin:0;}</style>';

        /** @var \XF\Entity\Option $option */
        $option = \XPress::xlink()->em()->create('XF:Option');
        $option->data_type = 'integer';
        $option->option_value = $categoryLink->content_id;
        $option->option_id = 'thxpress_empty';

        echo Forum::renderSelect($option, [
            'inputName' => 'term_meta[_xPressXFForumID]',
            'hintHtml' => '',
            'explainHtml' => '',
            'listedHtml' => ''
        ]);

        echo '<tr class="form-field"><th scope="row"><label for="term_meta[_xPressXFCategoryID]">' . \XPress::xlink()->phrase('thxpress_xf_category_id') . '</label></th>
                <td class="thxpress-category"><style>td.thxpress-category dl, td.thxpress-category dd {margin:0;}</style>';

        /** @var \XF\Repository\Node $nodeRepo */
        $nodeRepo = \XPress::xlink()->repository('XF:Node');

        $choices = $nodeRepo->getNodeOptionsData(true, ['Forum', 'Category'], 'option');
        $choices = array_map(function ($v) {
            $v['label'] = \XF::escapeString($v['label']);
            return $v;
        }, $choices);

        $data = [
            'choices' => $choices,
            'controlOptions' => [
                'name' => 'term_meta[_xPressXFCategoryID]',
                'value' => empty($term_meta['category']) ? 0 : $term_meta['category'],
            ],
            'rowOptions' => []
        ];

        echo \XPress::xlink()->templater()->formSelectRow($data['controlOptions'], $data['choices'],
            $data['rowOptions']);

        echo '<p class="description">' . \XPress::xlink()->phrase('thxpress_xf_category_id_explain') . '</p></td></tr>';

        /* Background color selection */
        echo '<tr class="form-field"><th scope="row"><label for="term_meta[_xPressCatBackgroundColor]">' . \XPress::xlink()->phrase('thxpress_category_background_color') . '</label></th>
                <td class="thxpress-forum"><style>td.thxpress-forum dl, td.thxpress-forum dd {margin:0;}</style>';

        $defaults = [
            'f44336',
            'E91E63',
            '9C27B0',
            '673AB7',
            '3F51B5',
            '2196F3',
            '03A9F4',
            '00BCD4',
            '009688',
            '4CAF50',
            '8BC34A',
            'CDDC39',
            'FFC107',
            'FF9800',
            'FF5722',
            '795548',
            '9E9E9E',
            '607D8B'
        ];

        echo '<input name="term_meta[_xPressCatBackgroundColor]" type="text" value="' . $term_meta['bg_color'] . '" class="color-picker" data-default-color="#' . $defaults[array_rand($defaults)] . '" />';

        echo '<p class="description">' . \XPress::xlink()->phrase('thxpress_category_background_color_explain') . '</p></td></tr>';

        /* Foreground color selection */
        echo '<tr class="form-field"><th scope="row"><label for="term_meta[_xPressCatTextColor]">' . \XPress::xlink()->phrase('thxpress_category_text_color') . '</label></th>
                <td class="thxpress-forum"><style>td.thxpress-forum dl, td.thxpress-forum dd {margin:0;}</style><select name="term_meta[_xPressCatTextColor]">';

        echo '<option value="#ffffff">' . \XPress::xlink()->phrase('thxpress_white') . '</option>';
        echo '<option value="#000000" ' . ($term_meta['text_color'] == '#000000' ? ' selected' : '') . '>' . \XPress::xlink()->phrase('thxpress_black') . '</option>';

        echo '</select><p class="description">' . \XPress::xlink()->phrase('thxpress_category_text_color_explain') . '</p></td></tr>';

    }
}