<?php

if (!is_active_sidebar('sidebar-1')) {
    return;
}

ob_start();
dynamic_sidebar('sidebar-1');
$sidebarContents = ob_get_clean();
\XPress::registry()->set('xpress.sidebar', $sidebarContents);
