<?php

if (ob_get_level()) {
    $output = ob_get_clean();
} else {
    $output = null;
}

ob_start();
wp_admin_bar_render();
$adminBar = ob_get_clean();
\XPress::app()->registry()->set('options.extra_navigation_bar', $adminBar);

$bodyClass = join(' ', get_body_class()) . (\XPress::app()->registry()->get('options.noWrapper') ? ' thxpress_noWrapper' : '');
\XPress::app()->registry()->set('options.body_class', $bodyClass);

ob_start();
wp_footer();
$footer = ob_get_clean();
\XPress::app()->registry()->set('options.footer', $footer);

\XPress::updateRegistryValues();
\XPress::app()->runApp($output);
