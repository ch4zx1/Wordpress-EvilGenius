<?php

namespace XPress\Action;

/**
 * Class AddMetaBoxes
 * @package XPress\Action
 */
class AddMetaBoxes
{
    /**
     *
     */
    public static function register()
    {
        add_action('add_meta_boxes', [self::class, 'actionAddMetaBoxes'], 10, 2);
    }

    /**
     * @param $post_type
     * @param $post
     */
    public static function actionAddMetaBoxes($post_type, $post)
    {
        foreach (\XPress::getCompatiblePostTypes() as $postType) {
            add_meta_box('_xpress',
                \XPress::xlink()->phrase('thxpress'),
                function ($post) {
                    $threadLink = \XPress::xlink()->entityLink('post', $post->ID);

                    /* Forum Select */
                    /** @var \XF\Repository\Node $nodeRepo */
                    $nodeRepo = \XPress::xlink()->repository('XF:Node');

                    $choices = $nodeRepo->getNodeOptionsData(true, 'Forum', 'option');
                    $choices = array_map(function ($v) {
                        $v['label'] = \XF::escapeString($v['label']);
                        return $v;
                    }, $choices);

                    echo '<p><label for="_xpressForumID">' . \XPress::xlink()->phrase('forum:') . '</label><br/>';
                    echo \XPress::xlink()->templater()->formSelect([
                        'name' => '_xpressForumID',
                        'value' => $threadLink && $threadLink->XFEntity ? $threadLink->XFEntity->node_id :
                            (isset(\XPress::xlink()->platformLink()->options['default_forum']) ? \XPress::xlink()->platformLink()->options['default_forum'] : -1)
                    ], $choices);
                    echo '</p>';


                    /** @var \XF\Repository\ThreadPrefix $prefixRepo */
                    $prefixRepo = \XPress::xlink()->repository('XF:ThreadPrefix');
                    /** @var \XF\Mvc\Entity\ArrayCollection $prefixes */
                    $prefixes = $prefixRepo->getPrefixListData();
                    $choices = [
                        [
                            'label' => \XPress::xlink()->phrase('(none)'),
                            'value' => 0
                        ]
                    ];
                    foreach ($prefixes['prefixGroups'] as $key => $group) {
                        if (!empty($prefixes['prefixesGrouped'][$key])) {
                            $choices[] = [
                                'disabled' => true,
                                'value' => -1,
                                'label' => $key ? $group->title : \XPress::xlink()->phrase('ungrouped')
                            ];

                            foreach ($prefixes['prefixesGrouped'][$key] as $prefix) {
                                /** @var \XF\Entity\ThreadPrefix $prefix */
                                $choices[] = [
                                    'value' => $prefix->prefix_id,
                                    'label' => "&nbsp;&nbsp;{$prefix->title}"
                                ];
                            }
                        }
                    }


                    echo '<p><label for="_xpressPrefixID">' . \XPress::xlink()->phrase('prefix:') . '</label><br/>';
                    echo \XPress::xlink()->templater()->formSelect([
                        'name' => '_xpressPrefixID',
                        'value' => $threadLink && $threadLink->XFEntity ? $threadLink->XFEntity->prefix_id :
                            (isset(\XPress::xlink()->platformLink()->options['default_prefix']) ? \XPress::xlink()->platformLink()->options['default_prefix'] : -1)
                    ], $choices);
                    echo '</p>';

                    /** Thread Id */
                    $threadId = $threadLink ? $threadLink->content_id : 0;

                    if (!$threadId) {
                        echo '<p><label for="_xpressAuthor">' . \XPress::xlink()->phrase('thxpress_thread_author:') . '</label><br/>';
                        echo '<input type="text" name="_xpressAuthor" id="_xpressAuthor" /></p>';
                    }

                    echo '<p><label for="_xpressThreadID">' . \XPress::xlink()->phrase('thxpress_thread_id:') . '</label><br/>';
                    echo "<input type='number' name='_xpressThreadID' id='_xpressThreadID' value='{$threadId}' /></p>";

                },
                $postType,
                'side',
                'core',
                1
            );
        }

        /** @var \XF\Repository\Navigation $navRepo */
        $navRepo = \XPress::xlink()->repository('XF:Navigation');
        $navChoices = $navRepo->getTopLevelEntries();

        foreach (get_post_types(array('public' => true)) as $postType) {
            add_meta_box(
                '_xPressSectionContext',
                \XPress::xlink()->phrase('navigation_section'),
                function ($post) use ($navChoices) {
                    $section = get_post_meta($post->ID, '_xPressSectionContext',
                        true) ?: '_default';

                    /** @noinspection PhpUndefinedMethodInspection */
                    $platformLink = \XPress::xlink()->platformLink();
                    $default = isset($platformLink->options['section_context']) ? $platformLink->options['section_context'] : '_default';
                    $default = isset($navChoices[$default]) && $default !== '_default' ? $navChoices[$default]->title : \XPress::xlink()->phrase('none');

                    echo '<select name="_xPressSectionContext">';
                    echo "<option value='0' " . ($section === 0 ? 'selected' : '') . ">" . \XPress::xlink()->phrase('default') . " ({$default})</option>";

                    foreach ($navChoices as $navChoice) {
                        if ($navChoice->navigation_id != '_default') {
                            echo "<option value='{$navChoice->navigation_id}' " . ($section === $navChoice->navigation_id ? 'selected' : '') . ">{$navChoice->title}</option>";
                        }
                    }

                    echo '</select>';
                },
                $postType,
                'side',
                'core',
                1
            );
        }

        foreach (\XPress::getCompatiblePostTypes() as $postType) {
            if ($post->post_status != 'publish') {
                add_meta_box(
                    '_xpressAuthorComment',
                    \XPress::xlink()->phrase('thxpress_author_comment'),
                    function ($post) {
                        $authorComment = get_post_meta($post->ID, '_xpressAuthorComment', true);
                        $includeNoExcerpt = get_post_meta($post->ID, '_xpressIncludeNoExcerpt',
                            true) ? 'checked' : '';

                        echo '<textarea name="_xpressAuthorComment" style="width: 100%">' . $authorComment . '</textarea>';
                        echo '<p class="description">' . \XPress::xlink()->phrase('thxpress_author_comment_explain') . '</p>';

                        echo '<label for="_xpressIncludeNoExcerpt"><input id="_xpressIncludeNoExcerpt" type="checkbox" value="1" name="_xpressIncludeNoExcerpt" ' . $includeNoExcerpt . '>' . \XPress::xlink()->phrase('thxpress_include_no_excerpt') . '</label>';
                        echo '<p class="description">' . \XPress::xlink()->phrase('thxpress_include_no_excerpt_explain') . '</p>';
                    },
                    $postType, 'advanced', 'core', 1);
            }
        }
    }

}