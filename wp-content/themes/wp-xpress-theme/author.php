<?php

$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $_GET['author_name']) : get_userdata(intval($author));

if (\XPress::xlink()->options()->thxpress_profile_redirect) {
    $user = \XPress::getXFUser($curauth);

    if ($user) {
        wp_redirect(\XPress::xlink()->buildLink('members', $user));
        exit;
    }
}

get_header(); ?>

    <div class="wrap">
        <div class="block p-body-header">
            <div class="block-container">
                <div class="block-body block-row">
                    <?php thxpress_get_author_block($curauth->ID, '',false); ?>
                </div>
            </div>
        </div>
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">

                <?php
                if (have_posts()) :

                    echo \XPress::xlink()->styleProperty('thxpress_above_author_page_article_list');

                    echo '<div class="xpress_articleList">';
                    /* Start the Loop */
                    while (have_posts()) {
                        the_post();
                        get_template_part('template-parts/post/content', 'excerpt');
                    }

                    echo '</div>';

                    echo \XPress::xlink()->ad('thxpress_below_author_page_article_list');

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
                endif; ?>

            </main>
        </div>
        <?php get_sidebar(); ?>
    </div>

<?php get_footer();
