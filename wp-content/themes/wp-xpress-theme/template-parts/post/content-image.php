<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php
        if ('post' === get_post_type()) {
            echo '<div class="entry-meta">';
            if (is_single()) {
                echo thxpress_posted_on();
            } else {
                echo thxpress_time_link();
                thxpress_edit_link();
            };
            echo '</div>';
        };

        ?>
    </header>

    <?php if ('' !== get_the_post_thumbnail() && !is_single()) : ?>
        <?php XPress::app()->registry()->set('options.lightbox', true); ?>
        <div data-lb-universal='1' data-lb-id='test' data-xf-init='lightbox'
             class="post-thumbnail lbContainer js-lbContainer">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('thxpress-featured-image'); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="entry-content">

        <?php if (is_single() || '' === get_the_post_thumbnail()) {

            the_content(sprintf(
                \XPress::xlink()->phrase('thxpress_continue_reading...'),
                get_the_title()
            ));

            wp_link_pages(array(
                'before' => '<div class="page-links">' . \XPress::xlink()->phrase('pages:'),
                'after' => '</div>',
                'link_before' => '<span class="page-number">',
                'link_after' => '</span>',
            ));

        };
        ?>

    </div>

    <?php
    if (is_single()) {
        xpress_entry_footer();
    }
    ?>

</article>
