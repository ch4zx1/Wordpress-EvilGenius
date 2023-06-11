<?php

use ThemeHouse\XLink\Entity\PlatformLink;

get_header();

$registryValues = XPress::registry()->get('xpress');

$query = explode(' ', get_search_query());
$args = [];
foreach ($query as $term) {
    $args [] = '<a href="' . get_search_link($term) . '"><em>' . $term . '</em></a>';
}
$args = join(', ', $args);

\XPress::app()->registry()->set('xpress',
    array_merge($registryValues, ['title' => \XPress::xlink()->phrase('search_results_for_query:') . " $args"]));

$query = get_search_query();
$xfUrl = \XPress::app()->option('boardUrl');
if (\XPress::app()->isXFInitialized()) {
    $xfToken = \XF::app()->get('csrf.token');
} else {
    $xfToken = null;
}

$platforms = \XF::finder('ThemeHouse\XLink:PlatformLink')->fetch();

$handlers = [];
foreach ($platforms as $platform) {
    /** @var PlatformLink $platform */
    if ($handler = $platform->getSearchHandler()) {
        $handlers[$platform->platform_id] = $handler;
    }
}

get_header(); ?>

    <form style="display: none;" id="thxpress_xf__search" action="<?php echo $xfUrl; ?>/search/search" method="post">
        <input type="text" name="keywords" value="<?php echo $query; ?>">
        <input type="hidden" name="_xfToken" value="<?php echo $xfToken; ?>">
    </form>

    <div class="wrap">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">

                <div class="tabs tabs--standalone">
                    <a class="tabs-tab" onclick="document.getElementById('thxpress_xf__search').submit()"
                       href="#"><?php echo \XPress::xlink()->phrase('thxpress_search_the_forum') ?></a>
                    <?php foreach ($handlers as $pId => $handler) {
                        /** @var \ThemeHouse\XLink\RemoteHandler\AbstractSearch $handler */
                        $active = $pId == \XPress::xlink()->platformLink()->platform_id;
                        echo "<a class='tabs-tab " . ($active ? 'is-active' : '') . "' href='{$handler->getSearchLink(['search_query' => $query])}'>{$handler->getSearchTabPhrase()}</a>";
                    } ?>
                </div>

                <?php
                if (have_posts()) :
                    echo '<div class="block-container"><div class="block-body">';
                    while (have_posts()) : the_post();

                        \XPress::app()->registry()->set('breadcrumbs', [
                            [
                                'value' => \XPress::xlink()->phrase('search'),
                                'href' => $xfUrl . '/search'
                            ]
                        ]);

                        get_template_part('template-parts/post/content-search-excerpt', get_post_format());

                    endwhile;

                    echo '</div></div>';

                    ?>
                    <div class="block-outer block-outer--after">
                        <?php
                        global $wp, $wp_query;
                        /** @noinspection PhpUndefinedMethodInspection */
                        /** @noinspection PhpUndefinedVariableInspection */
                        $escape = null;
                        echo \XPress::xlink()->templater()->fnWPPageNav(null, $escape, [
                            'page' => get_query_var('paged') ? intval(get_query_var('paged')) : 1,
                            'perPage' => get_option('posts_per_page'),
                            'pages' => isset($wp_query->max_num_pages) ? $wp_query->max_num_pages : 1,
                            'variantClass' => 'block-outer-main',
                            'link' => preg_replace('#(page/\d+)#', '', get_search_link()),
                            'pageBit' => 'page/',
                            'anchor' => null
                        ]);
                        ?>
                    </div>
                <?php

                else : ?>

                    <div class="blockMessage">
                        <?php get_search_form(); ?>
                        <p>
                        <?php echo \XPress::xlink()->phrase('no_results_found') ?>
                        </p>
                    </div>
                    <?php

                endif;
                ?>

            </main>
        </div>
    </div>

<?php get_footer();
