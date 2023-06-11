<article id="post-<?php the_ID(); ?>" <?php post_class(); // TODO: Phrases ?>>
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

    <?php
    $content = apply_filters('the_content', get_the_content());
    $audio = false;

    if (false === strpos($content, 'wp-playlist-script')) {
        $audio = get_media_embedded_in_content($content, array('audio'));
    }

    ?>

    <?php if ('' !== get_the_post_thumbnail() && !is_single()) : ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('thxpress-featured-image'); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="entry-content">

        <?php
        if (!is_single()) {

            if (!empty($audio)) {
                foreach ($audio as $audio_html) {
                    echo '<div class="entry-audio">';
                    echo $audio_html;
                    echo '</div>';
                }
            };

        };

        if (is_single() || empty($audio)) {

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