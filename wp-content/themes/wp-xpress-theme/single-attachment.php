<?php get_header(); ?>
    <div class="wrap">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">

                <?php
                while (have_posts()) :
                    the_post();
                    /** @var WP_Term $categories */
                    $categories = get_the_category();
                    if (!empty($categories)) {
                        \XPress::registry()->set('categories', $categories);
                        if ($category = reset($categories)) {
                            /** @var WP_Term $category */
                            \XPress::registry()->set('breadcrumbs', [
                            [
                                'value' => html_entity_decode($category->name),
                                'href' => get_category_link($category->cat_ID)
                            ]
                            ]);
                        }
                    }

                    get_template_part('template-parts/post/content', get_post_format());

                    if (comments_open() || get_comments_number()) {
                        comments_template();
                    }

                    the_post_navigation(array(
                        'prev_text' => '<span class="screen-reader-text">' . \XPress::xlink()->phrase('prev') . '</span><span aria-hidden="true" class="nav-subtitle">' . \XPress::xlink()->phrase('prev') . '</span><span class="nav-title"><span class="nav-title-icon-wrapper"></span>%title</span>',
                        'next_text' => '<span class="screen-reader-text">' . \XPress::xlink()->phrase('next') . '</span><span aria-hidden="true" class="nav-subtitle">' . \XPress::xlink()->phrase('next') . '</span><span class="nav-title">%title<span class="nav-title-icon-wrapper"></span></span>',
                    ));
                endwhile;
                ?>

            </main>
        </div>
    </div>

<?php
ob_start();
dynamic_sidebar('sidebar-4');
$sidebarContents = ob_get_clean();

\XPress::registry()->bulkSet([
    'xpress' => [
        'sidebar' => $sidebarContents
    ],
    'options.noH1' => true,
    'options.description' => '',
]);

get_footer();
