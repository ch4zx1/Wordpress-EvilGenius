<?php
ob_start();

ob_start();
wp_head();
$head = ob_get_clean();
\XPress::registry()->set('options.header', $head);
\XPress::registry()->set('options.title', get_the_title());

if ($post = get_post()) {
    $options = \XPress::app()->options();
    $sectionContext = get_post_meta($post->ID, '_xPressSectionContext',
        true) ?: null;

    $postId = $post->ID;
    while(!$sectionContext) {
        $postId = wp_get_post_parent_id($postId);

        if(!$postId) {
            break;
        }

        $sectionContext = get_post_meta($postId, '_xPressSectionContext',
            true) ?: null;
    }

    if ($sectionContext) {
        \XPress::registry()->set('options.section_context', $sectionContext);
    }
}