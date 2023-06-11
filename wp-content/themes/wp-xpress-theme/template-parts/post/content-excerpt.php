<?php

$avatarSize = \XPress::getStyleProperty('xpress_articleThumbnailSize');
$excerptLimit = \XPress::getStyleProperty('thxpress_excerptLimit');
$excerptBool = \XPress::getStyleProperty('thxpress_excerpt');

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('block'); ?>>
    <div class="block-container">
        <div class="block-body">
            <div class="media__container block-row">
                <?php if (get_the_post_thumbnail($post)): ?>
                    <div class="media__object media--left">
                        <div class="xpress_articleImage--full">
                            <a href="<?php echo esc_url(get_permalink()); ?>">
                                <?php the_post_thumbnail('medium_large'); ?>
                            </a>
                        </div>
                        <div class="xpress_articleImage--thumbnail">
                            <a href="<?php echo esc_url(get_permalink()); ?>">
                                <?php the_post_thumbnail($avatarSize); ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="media__body">
                    <?php thxpress_get_the_category_list(); ?>

                    <header class="entry-header">
                        <?php the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">',
                            esc_url(get_permalink())), '</a></h2>'); ?>

                        <?php if ('post' === get_post_type()) : ?>
                            <ul class="entry-meta listInline listInline--bullet">
                                <li>
                                    <a class="article__author"
                                       href="<?php echo get_author_posts_url(get_the_author_meta('ID'),
                                           get_the_author_meta('user_nicename')); ?>"><?php echo thxpress_author_name(get_the_author_meta('ID')); ?></a>
                                </li>
                                <li>
                                    <?php echo thxpress_time_link(); ?>
                                </li>
                                <li>
                                    <a href="<?php echo esc_url(get_permalink()); ?>#comments"><?php echo thxpress_comment_count($post) . ' ' . \XPress::xlink()->phrase('comments'); ?></a>
                                </li>
                                <?php if (current_user_can('edit_post', $post->ID)) : ?>
                                    <li class="xpress_editLink">
                                        <?php thxpress_edit_link(); ?>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        <?php elseif ('page' === get_post_type() && get_edit_post_link()) : ?>
                            <div class="entry-meta">
                                <?php thxpress_edit_link(); ?>
                            </div>
                        <?php endif; ?>

                    </header>

                    <div class="entry-summary">
                        <?php

                        if ($excerptBool) {
                            add_filter('excerpt_length', function () use ($excerptLimit) {
                                return $excerptLimit;
                            }, 999);
                            echo strip_shortcodes(get_the_excerpt());
                        } else {
                            the_content();
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
