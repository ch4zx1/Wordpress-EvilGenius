<?php

$authorAvatar = get_avatar($post->post_author);
?>

<div class="block-row block-row--separated">
    <div class="contentRow">
        <div class="contentRow-figure">
            <div class="avatar avatar--s">
                <?php if (has_post_thumbnail()) {
                    the_post_thumbnail('thumbnail');
                } else {
                    echo $authorAvatar;
                } ?>
            </div>
        </div>
        <div class="contentRow-main">
            <?php thxpress_get_the_category_list(); ?>

            <?php the_title(sprintf('<div class="contentRow-title"><a href="%s" rel="bookmark">',
                esc_url(get_permalink())), '</a></div>'); ?>

            <div class="contentRow-snippet">
                <?php the_excerpt() ?>
            </div>

            <div class="contentRow-minor contentRow-minor--hideLinks">
                <ul class="listInline listInline--bullet">
                    <?php if ('post' === get_post_type()) : ?>
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

                    <?php elseif ('page' === get_post_type() && get_edit_post_link()) : ?>
                        <li>
                            <?php thxpress_edit_link(); ?>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
