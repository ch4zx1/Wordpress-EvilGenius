<?php
/**
 * Template Name: Front Page
 */

get_header();

$registryValues = XPress::app()->registry()->get('xpress');

\XPress::registry()->bulkSet([
    'options.title' => \XPress::xlink()->phrase('thxpress_home'),
    'xpress.front_page' => true,
    'options.noH1' => true,
]);

$excludedFromMain = array();

$indexPosts = array();
$thxpress_dotsNavigation = \XPress::getStyleProperty('thxpress_dotsNavigation');
$xpress_featuredArticleStyle = \XPress::getStyleProperty('xpress_featuredArticleStyle');
$thxpress_carouselItems = \XPress::getStyleProperty('thxpress_carouselItems');
$featuredAmount = \XPress::getStyleProperty('xpress_featuredItemsCount');

$featured_posts = get_posts(array(
    'tag' => array('featured'),
    'numberposts' => $featuredAmount,
));

foreach ($featured_posts as $key => $featuredpost) {
    array_push($excludedFromMain, $featuredpost->ID);
}

function showFeaturedPosts($featured_posts, $numposts, $classes = "")
{
    $fpCount = 0;
    global $post;

    echo "<div class='{$classes}'>";
    foreach ($featured_posts as $key => $post):
        $fpCount++;
        unset($featured_posts[$key]);

        setup_postdata($post);
        get_template_part('template-parts/post/content-featuredExcerpt');
        wp_reset_postdata();

        if ($fpCount === $numposts) {
            break;
        }
    endforeach;
    echo "</div>";
}

?>

<?php
ob_start();
echo '<div class="xpress_featuredArticles block">';
echo \XPress::xlink()->ad('thxpress_before_featured_articles');

switch ($xpress_featuredArticleStyle) {
    case 'carousel':
        showFeaturedPosts($featured_posts, $featuredAmount, "owl-carousel");
        break;

    case 'grid':
        showFeaturedPosts($featured_posts, $featuredAmount, "xpress_articleList__featured");
        break;
}

echo \XPress::xlink()->ad('thxpress_after_featured_articles');
echo '</div>';

$aboveWrapperContents = ob_get_clean();
\XPress::app()->registry()->set('options.aboveWrapper', $aboveWrapperContents);
?>

    <div id="primary" class="content-area test">
        <main id="main" class="site-main" role="main">

            <?php
            if (get_query_var('paged')) {
                $paged = get_query_var('paged');
            } elseif (get_query_var('page')) {
                $paged = get_query_var('page');
            } else {
                $paged = 1;
            }

            query_posts(array(
                'post__not_in' => $excludedFromMain,
                'posts_per_page' => get_option('posts_per_page'),
                'paged' => $paged
            ));

            if (have_posts()) :
                echo '<div class="xpress_articleList">';
                while (have_posts()) :
                    the_post();
                    get_template_part('template-parts/post/content-snippet', get_post_format());

                endwhile;
                echo '</div>';
            else :
                get_template_part('template-parts/post/content', 'none');
            endif;
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
        </main>
    </div>

<?php if ($xpress_featuredArticleStyle == 'carousel') :
    wp_add_inline_script('owl_carousel_script', "
        jQuery(\".owl-carousel\").owlCarousel({
            items: $thxpress_carouselItems,
            responsive: {
                0: {
                    items : 1,
                },
                900: {
                    option1 : $thxpress_carouselItems,
                },
            },
            margin: 10,
            loop: true,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            dots: $thxpress_dotsNavigation,
        });
    ", 'after');
endif;

ob_start();
dynamic_sidebar('sidebar-5');
$sidebarContents = ob_get_clean();

\XPress::xlink()->registry()->bulkSet([
        'xpress' => [
            'sidebar' => $sidebarContents
        ]
    ]
);

get_footer();
