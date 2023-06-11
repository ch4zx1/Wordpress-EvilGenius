<article id="post-<?php the_ID(); ?>" <?php post_class();?>>
    <header class="entry-header">
        <?php
        if ('post' === get_post_type()) {
            echo '<div class="entry-meta">';
            if (is_single()) {
                echo thxpress_posted_on();
            } else {
                echo thxpress_time_link();
                thxpress_edit_link();
            }
            echo '</div>';
        };

        if (is_single()) {
            the_title('<h1 class="entry-title">', '</h1>');
        } elseif (is_front_page() && is_home()) {
            the_title('<h3 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">',
                '</a></h3>');
        } else {
            the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">',
                '</a></h2>');
        }
        ?>
    </header>

    <?php
    $content = apply_filters('the_content', get_the_content());
    $video = false;

    if (false === strpos($content, 'wp-playlist-script')) {
        $video = get_media_embedded_in_content($content, array('video', 'object', 'embed', 'iframe'));
    }
    ?>

    <?php if ('' !== get_the_post_thumbnail() && !is_single() && empty($video)) : ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('thxpress-featured-image'); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="entry-content">

        <?php
        if (!is_single()) {

            if (!empty($video)) {
                foreach ($video as $video_html) {
                    echo '<div class="entry-video">';
                    echo $video_html;
                    echo '</div>';
                }
            };

        };

        if (is_single() || empty($video)) {

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
