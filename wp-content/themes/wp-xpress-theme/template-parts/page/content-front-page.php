<article id="post-<?php the_ID(); ?>" <?php post_class('twentyseventeen-panel '); ?> >

    <?php if (has_post_thumbnail()) :
        $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thxpress-featured-image');

        $ratio = $thumbnail[2] / $thumbnail[1] * 100;
        ?>

        <div class="panel-image" style="background-image: url(<?php echo esc_url($thumbnail[0]); ?>);">
            <div class="panel-image-prop" style="padding-top: <?php echo esc_attr($ratio); ?>%"></div>
        </div>

    <?php endif; ?>

    <div class="panel-content">
        <div class="wrap">
            <header class="entry-header">
                <?php the_title('<h2 class="entry-title">', '</h2>'); ?>

                <?php thxpress_edit_link(get_the_ID()); ?>

            </header>

            <div class="entry-content">
                <?php
                // TODO: Phrases
                the_content(sprintf(
                    \XPress::xlink()->phrase('thxpress_continue_reading...'),
                    get_the_title()
                ));
                ?>
            </div>

        </div>
    </div>

</article>
