<?php

/** THEME SETUP */
add_action('after_setup_theme', function () {
    add_image_size('thxpress-featured-image', 2000, 1200, true);

    add_theme_support('html5', array(
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    add_theme_support('post-formats', array(
        'aside',
        'image',
        'video',
        'quote',
        'link',
        'gallery',
        'audio',
    ));
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
});

/** PINGBACK SUPPORT */
add_action('wp_head', function () {
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">' . "\n", get_bloginfo('pingback_url'));
    }
});

/** REMOVE CUSTOMIZATION MENU FROM ADMIN BAR */
add_action('wp_before_admin_bar_render', function ($wp_customize) {
    global $wp_admin_bar;
    /** @var WP_Admin_Bar $wp_admin_bar */
    $wp_admin_bar->remove_menu('customize');
}, PHP_INT_MAX, 1);

/* UNREGISTER DEFAULT WIDGETS */
add_action('widgets_init', function () {
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_Recent_Comments');
}, 1);

/** REGISTER SIDEBAR POSITIONS */
add_action('widgets_init', function () {

    register_sidebar(array(
        'name' => \XPress::xlink()->phrase('thxpress_blog_sidebar'),
        'id' => 'sidebar-1',
        'description' => \XPress::xlink()->phrase('thxpress_blog_sidebar_desc'),
        'before_widget' => '<div id="%1$s" class="block block-xpress %2$s"><div class="block-container"><div class="block-body"><div class="block-row">',
        'after_widget' => '</div></div></div></div>',
        'before_title' => '</div></div><h3 class="block-minorHeader">',
        'after_title' => '</h3><div class="block-body"><div class="block-row">',
    ));

    register_sidebar(array(
        'name' => \XPress::xlink()->phrase('thxpress_article_view_sidebar'),
        'id' => 'sidebar-2',
        'description' => \XPress::xlink()->phrase('thxpress_article_view_sidebar_desc'),
        'before_widget' => '<div id="%1$s" class="block block-xpress %2$s"><div class="block-container"><div class="block-body"><div class="block-row">',
        'after_widget' => '</div></div></div></div>',
        'before_title' => '</div></div><h3 class="block-minorHeader">',
        'after_title' => '</h3><div class="block-body"><div class="block-row">',
    ));

    register_sidebar(array(
        'name' => \XPress::xlink()->phrase('thxpress_page_sidebar'),
        'id' => 'sidebar-3',
        'description' => \XPress::xlink()->phrase('thxpress_page_sidebar_desc'),
        'before_widget' => '<div id="%1$s" class="block block-xpress %2$s"><div class="block-container"><div class="block-body"><div class="block-row">',
        'after_widget' => '</div></div></div></div>',
        'before_title' => '</div></div><h3 class="block-minorHeader">',
        'after_title' => '</h3><div class="block-body"><div class="block-row">',
    ));

    register_sidebar(array(
        'name' => \XPress::xlink()->phrase('thxpress_single_attachment_sidebar'),
        'id' => 'sidebar-4',
        'description' => \XPress::xlink()->phrase('thxpress_single_attachment_sidebar_desc'),
        'before_widget' => '<div id="%1$s" class="block block-xpress %2$s"><div class="block-container"><div class="block-body"><div class="block-row">',
        'after_widget' => '</div></div></div></div>',
        'before_title' => '</div></div><h3 class="block-minorHeader">',
        'after_title' => '</h3><div class="block-body"><div class="block-row">',
    ));

    register_sidebar(array(
        'name' => \XPress::xlink()->phrase('thxpress_front_page_sidebar'),
        'id' => 'sidebar-5',
        'description' => \XPress::xlink()->phrase('thxpress_front_page_sidebar_desc'),
        'before_widget' => '<div id="%1$s" class="block block-xpress %2$s"><div class="block-container"><div class="block-body"><div class="block-row">',
        'after_widget' => '</div></div></div></div>',
        'before_title' => '</div></div><h3 class="block-minorHeader">',
        'after_title' => '</h3><div class="block-body"><div class="block-row">',
    ));
});

/** LOAD OWL CAROUSEL */
if (\XPress::getStyleProperty('xpress_featuredArticleStyle') == 'carousel') {
    \XPress::registry()->loadJS('themehouse/xpress/owl-carousel/owl.carousel.min.js');
    \XPress::registry()->loadCSS('thxpress_owlCarousel.less');
}

/** LOAD MASONRY */
if (\XPress::getStyleProperty('xpress_articleListLayout') == 'masonry') {
    \XPress::registry()->loadJS('themehouse/xpress/masonry/masonry.pkgd.min.js');
    \XPress::registry()->loadJS('themehouse/xpress/masonry/imagesloaded.pkgd.min.js');
}
