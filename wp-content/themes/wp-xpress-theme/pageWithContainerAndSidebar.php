<?php
/**
 * Template Name: Page (Container & Sidebar)
 */

get_header();
echo \XPress::xlink()->ad('thxpress_above_page_content');
?>

    <div class="wrap">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
                <div class="block">
                    <div class="block-container">
                        <div class="block-body block-row">
                            <?php
                            while (have_posts()) {
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

                                get_template_part('template-parts/page/content', 'page');

                                if (comments_open() || get_comments_number()) {
                                    comments_template();
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

<?php

echo \XPress::xlink()->ad('thxpress_below_page_content');

get_sidebar('sidebar-3');
get_footer();
