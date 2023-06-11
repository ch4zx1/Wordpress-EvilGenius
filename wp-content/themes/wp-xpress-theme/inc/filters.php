<?php

/** FONT CLOUD */
add_filter('widget_tag_cloud_args', function ($args) {
    $args['largest'] = 1;
    $args['smallest'] = 1;
    $args['unit'] = 'em';
    $args['format'] = 'list';

    return $args;
});

/** STYLE PASSWORD PROTECTION FORM */
add_filter('the_password_form', function () {
    global $post;
    $label = 'pwbox-' . (empty($post->ID) ? rand() : $post->ID);

    \XPress::app()->registry()->set('xpress.aboveWrapper', "
	<div class=\"blockMessage blockMessage--warning blockMessage--iconic\">
		" . \XPress::xlink()->phrase('thxpress_content_is_password_protected') . "
	</div>
	");

    $formAction = get_option('siteurl') . '/wp-login.php?action=postpass';

    return "
        <script>
            jQuery('div.block, div.block-container, div.block-body').removeClass('block block-container block-body block-row');
        </script>
        <style>
            .entry-content p {display:none;}
        </style>
        <form class='block post-password-form' action='{$formAction}' method='post'>
            <div class='block-container'>
                <div class='block-body'>
                <dl class='formRow formRow--input'>
                    <dt>
                        <div class='formRow-labelWrapper'>
                            <label class='formRow-label' for='_xfUid-2-1533206758'>" . \XPress::xlink()->phrase('password') . "</label>
                        </div>
                    </dt>
                    <dd>
                        <input type='password' class='input' name='post_password' id='{$label}'>
                    </dd>
                </dl>
                </div>
                <dl class='formRow formSubmitRow formSubmitRow--sticky' data-xf-init='form-submit-row'>
                    <dt></dt>
                    <dd>
                        <div class='formSubmitRow-main'>
                            <div class='formSubmitRow-bar'></div>
                            <div class='formSubmitRow-controls'>
                                <button class='button button--primary button--icon button--icon--save rippleButton'>
                                    <span class='button-text'>" . \XPress::xlink()->phrase('save') . "</span>
                                </button>
                            </div>
                        </div>
                    </dd>
                </dl>
            </div>
        </form>
    ";
}, 10, 0);

/** --------- */
/** IMAGE PROCESSORS */
/** --------- */
add_filter('wp_calculate_image_sizes', function ($sizes, $size) {
    $width = $size[0];

    if (740 <= $width) {
        $sizes = '(max-width: 706px) 89vw, (max-width: 767px) 82vw, 740px';
    }

    if (is_active_sidebar('sidebar-1') || is_archive() || is_search() || is_home() || is_page()) {
        if (!(is_page() && 'one-column' === get_theme_mod('page_options')) && 767 <= $width) {
            $sizes = '(max-width: 767px) 89vw, (max-width: 1000px) 54vw, (max-width: 1071px) 543px, 580px';
        }
    }

    return $sizes;
}, 10, 2);

add_filter('get_header_image_tag', function ($html, $header, $attr) {
    if (isset($attr['sizes'])) {
        $html = str_replace($attr['sizes'], '100vw', $html);
    }
    return $html;
}
    , 10, 3);

add_filter('wp_get_attachment_image_attributes', function ($attr, $attachment, $size) {
    if (is_archive() || is_search() || is_home()) {
        $attr['sizes'] = '(max-width: 767px) 89vw, (max-width: 1000px) 54vw, (max-width: 1071px) 543px, 580px';
    } else {
        $attr['sizes'] = '100vw';
    }

    return $attr;
}, 10, 3);

/** --------- */
/** LIGHTBOX */
/** --------- */

/** INIT XF LIGHTBOX ON WP GALLERY */
add_filter('gallery_style', function ($output) {
    \XPress::app()->registry()->set('options.lightbox', true);
    $output = str_replace("class='",
        "data-lb-universal='1' data-lb-id='test' data-xf-init='lightbox' class='lbContainer js-lbContainer ", $output);
    return $output;
}, 10, 1);

/** INIT XF LIGHTBOX ON WP ATTACHMENTS */
add_filter('wp_get_attachment_link', function ($html, $id) {
    \XPress::app()->registry()->set('options.lightbox', true);
    $html = str_replace('<a', '<a class="js-lbImage"', $html);

    $attachmentData = wp_get_attachment_image_src($id, 'full');
    $url = $attachmentData[0];
    $html = preg_replace("/href='.*?'/", "href='{$url}'", $html);

    return $html;
}, 10, 2);

/** --------- */
/** COMMENTS */
/** --------- */

/** FORMAT COMMENT REPLY FIELD */
add_filter('comment_form_field_comment', function () {
    \XPress::registry()->loadJS('themehouse/xpress/comment.js');
    return '<div data-xf-init="xpress-comment-form">' . \XPress::getEditorInstance('comment_content', 'comment') . '</div>';
}, 8);

/** READ XF FROALA EDITOR CONTENT ON SAVE */
add_filter('preprocess_comment', function ($data) {
    $data['comment_content'] = \XPress::getEditorContent('comment_content') ?: $data['comment_content'];

    if (empty($data['comment_content']) || $data['comment_content'] === 'x') {
        wp_die(\XPress::xlink()->phrase('xpress_empty_comment_error'), \XPress::xlink()->phrase('xpress_empty_comment'));
    }

    return $data;
}, 99, 1);

/** --------- */
/** EXCERPTS */
/** --------- */

add_filter('excerpt_more', function ($link)
{
    if (is_admin()) {
        return $link;
    }

    $link = sprintf('<p class="link-more"><a href="%1$s" class="more-link">%2$s</a></p>',
        esc_url(get_permalink(get_the_ID())),
        \XPress::xlink()->phrase('thxpress_read_more...')
    );
    return ' &hellip; ' . $link;
});