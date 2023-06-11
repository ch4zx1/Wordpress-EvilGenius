<?php
/**
 * Template Name: Category
 */
get_header();
?>

    <div class="wrap">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
                <?php if (\XPress::getStyleProperty('xpress_categoryScroller')) : ?>
                    <div class="block xpress_categoryToggle hScroller" data-xf-init="h-scroller">
                        <div class="hScroller-scroll">
                            <?php thxpress_get_all_categories(); ?>
                        </div>
                    </div>
                <?php
                endif;
                if (have_posts()):
                    echo '<div class="xpress_articleList">';
                    while (have_posts()) {
                        the_post();
                        get_template_part('template-parts/post/content', 'excerpt');
                    }

                    /** @var WP_Term $categories */
                    $categories = get_the_category();
                    if (!empty($categories)) {
                        \XPress::registry()->set('categories', $categories);
                    }
                    echo '</div>';
                    ?>
                    <div class="block-outer block-outer--after">
                        <?php
                        global $wp, $wp_query;
                        /** @noinspection PhpUndefinedMethodInspection */
                        /** @noinspection PhpUndefinedVariableInspection */
                        $escape = null;
                        echo \XPress::xlink()->templater()->fnWPPageNav(null, $escape, [
                            'page' => get_query_var('paged') ? intval(get_query_var('paged')) : 1,
                            'perPage' => get_option('posts_per_page'),
                            'pages' => isset($wp_query->max_num_pages) ? $wp_query->max_num_pages : 1,
                            'variantClass' => 'block-outer-main',
                            'link' => preg_replace('#(/page/\d+)#', '', home_url($wp->request)),
                            'pageBit' => '/page/',
                            'anchor' => null
                        ]);
                        ?>
                    </div>
                <?php
                else :
                    get_template_part('template-parts/post/content', 'none');
                endif; ?>

            </main>
        </div>
        <?php get_sidebar(); ?>
    </div>

<?php get_footer();