<?php

$author = thxpress_author_name(get_the_author_meta('ID'));
$date = get_the_date();

$authorId = get_the_author_meta('ID');
$avatar = get_avatar_url($authorId);

$authorLink = get_author_posts_url($authorId, get_the_author_meta('user_nicename'));

$registryValues = \XPress::xlink()->registry()->get('xpress');

XPress::xlink()->registry()->set('xpress', array_merge(
    $registryValues, [
    'description' => "
		 	<div class='p-description'>
				<a href=''><img src='{$avatar}' /></a>
				{$date}
			</div>",
]));

?>


<article id="post-<?php the_ID(); ?>" <?php post_class('block article-full'); ?>>
    <div class="block-container">

        <header class="entry-header"
                style="background-image: url(<?php the_post_thumbnail_url('thxpress-featured-image'); ?>)">

            <div class="xpress_entry-header__content">

                <?php thxpress_get_the_category_list(); ?>

                <h1 class="page-title"><?php single_post_title(); ?></h1>

                <div class="entry-meta">
                    <div class="entry-meta__author">
                        <a href="<?php echo $authorLink ?>">
                            <?php echo get_avatar($authorId, 24) ?></a>
                        <a href="<?php echo $authorLink; ?>">
                            <?php echo thxpress_author_name(get_the_author_meta('ID')) ?>
                        </a>
                    </div>


                    <?php

                    if ('post' === get_post_type()) {
                        echo '<div class="entry-meta__postDate">';
                        if (is_single()) {
                            echo thxpress_time_link();
                            thxpress_edit_link();
                        } else {
                            echo thxpress_time_link();
                            thxpress_edit_link();
                        };
                        echo '</div>';
                    };
                    ?>
                </div>
            </div>
        </header>

        <div class="block-body block-row">
            <?php if (thxpress_edit_time_link()) : ?>
                <div class="xpress_updateLink messageNotice">
                    <?php echo thxpress_edit_time_link(); ?>
                </div>
            <?php endif; ?>
            <?php XPress::app()->registry()->set('options.lightbox', true); ?>
            <div data-lb-universal='1' data-lb-id='test' data-xf-init='lightbox'
                 class="entry-content lbContainer js-lbContainer">
                <?php

                if(is_single()) {
                    echo \XPress::xlink()->ad('thxpress_above_article_content');
                }

                the_content(sprintf(
                    \XPress::xlink()->phrase('thxpress_continue_reading...'),
                    get_the_title()
                ));

                if(is_single()) {
                    echo \XPress::xlink()->ad('thxpress_below_article_content');
                }

                wp_link_pages(array(
                    'before' => '<div class="page-links">' . \XPress::xlink()->phrase('pages:'),
                    'after' => '</div>',
                    'link_before' => '<span class="page-number">',
                    'link_after' => '</span>',
                ));
                ?>

                <?php

                thxpress_get_author_block($authorId, $authorLink);

                if(is_single()) {
                    echo \XPress::xlink()->ad('thxpress_after_author_block');
                }
                ?>
            </div>
        </div>

        <?php
        if (is_single()) {
            xpress_entry_footer();
        }
        ?>
    </div>

</article>
