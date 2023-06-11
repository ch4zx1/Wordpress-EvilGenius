<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage THXpress
 * @since 1.0.0
 * @version 1.0.0 Patch Level 2
 */

$xpress_categoryScroller = \XPress::getStyleProperty('xpress_categoryScroller');
$registryValues = XPress::registry()->get('xpress');
get_header();
?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

            <?php if ($xpress_categoryScroller) : ?>
                <div class="block xpress_categoryToggle hScroller" data-xf-init="h-scroller">
                    <div class="hScroller-scroll">
                        <?php thxpress_get_all_categories(); ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php
            if (have_posts()) :
                echo '<div class="xpress_articleList">';
                while (have_posts()) {
                    the_post();
                    get_template_part('template-parts/post/content-excerpt', get_post_format());
                }
                echo '</div>';

                ?>
                <div class="block-outer block-outer--after">
                    <?php
                    global $wp, $wp_query;
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

            endif;
            ?>

        </main>
    </div>
<?php
get_sidebar();
get_footer();
