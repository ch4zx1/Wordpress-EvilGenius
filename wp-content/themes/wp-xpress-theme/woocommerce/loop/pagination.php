<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.3.1
 */

if (!defined('ABSPATH')) {
    exit;
}

$total = isset($total) ? $total : wc_get_loop_prop('total_pages');
if ($total <= 1) {
    return;
}
?>
<div class="block">
    <div class="block-outer">
        <div class="block-outer-main">
            <?php
            echo \XPress::xlink()->templater()->fnWPPageNav(null, $escape, [
                'page' => max(1, isset($current) ? $current : wc_get_loop_prop('current_page')),
                'perPage' => 5,
                'pages' => $total,
                'variantClass' => 'block-outer-main',
                'link' => '',
                'pageBit' => str_replace('%#%', '', isset($base) ? $base : esc_url_raw(str_replace(999999999, '%#%',
                    remove_query_arg('add-to-cart', get_pagenum_link(999999999, false))))),
                'anchor' => null
            ]);
            ?>
        </div>
    </div>
</div>
