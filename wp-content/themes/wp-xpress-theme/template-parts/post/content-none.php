<section class="no-results not-found">
    <header class="page-header">
        <h1 class="page-title"><?php echo \XPress::xlink()->phrase('requested_page_not_found'); ?></h1>
    </header>
    <div class="page-content">
        <?php
        if (is_home() && current_user_can('publish_posts')) : ?>
            <p>
                <?php
                echo \XPress::xlink()->phrase('thxpress_publish_first_post',
                    ['url' => esc_url(admin_url('post-new.php'))]);
                ?>
            </p>
        <?php else : ?>

            <p><?php echo \XPress::xlink()->phrase('requested_page_not_found') ?></p>
            <?php
            get_search_form();

        endif; ?>
    </div>
</section>
