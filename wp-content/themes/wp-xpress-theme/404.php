<?php

get_header();
\XPress::setResponseCode(404);

?>
    <div class="wrap">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">

                <section class="error-404 not-found">
                    <header class="page-header">
                        <h1 class="page-title">404</h1>
                    </header>
                    <div class="page-content">
                        <p><?php \XPress::xlink()->phrase('requested_page_not_found'); ?></p>

                        <?php get_search_form(); ?>

                    </div>
                </section>
            </main>
        </div>
    </div>
<?php get_footer();
