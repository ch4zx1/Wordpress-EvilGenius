<?php

$hideExcerpt = \XPress::getStyleProperty('xpress_removeFeaturedExcerpt');
$hideMeta = \XPress::getStyleProperty('xpress_removeFeaturedMeta');
ob_start();
the_post_thumbnail_url('large');
$thumb = ob_get_clean();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('block xpress_article--featured' . ($thumb ? ' has-image' : '')); ?>>
    <div class="block-container">
        <div class="block-body" style="<?php echo $thumb ? "background-image: url({$thumb})" : "" ?>">
            <div class="block-row">
                <?php thxpress_get_the_category_list(); ?>

                <header class="entry-header">
                    <?php the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">',
                        esc_url(get_permalink())), '</a></h2>'); ?>


                    <?php if ($hideMeta != true) : ?>
                        <div class="entry-meta">
                            <a class="article__author" href="<?php echo get_author_posts_url(get_the_author_meta('ID'),
                                get_the_author_meta('user_nicename')); ?>"><?php echo thxpress_author_name(get_the_author_meta('ID')); ?></a>,
                            <?php
                            echo thxpress_time_link();
                            thxpress_edit_link();
                            ?>
                        </div>
                    <?php endif; ?>

                </header>

                <?php if ($hideExcerpt != true) : ?>
                    <div class="entry-summary">
                        <?php echo thxpress_get_featured_excerpt(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</article>
