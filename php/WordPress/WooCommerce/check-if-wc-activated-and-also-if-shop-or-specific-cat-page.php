<?php

/**
 * If woocommerce activated, and also if shop or specefic category page
 */

add_action('wp_footer', function () {
    if (class_exists('WooCommerce')) {
        if (is_shop() || is_product_category('category_slug')) { // Or pass array for multiple categories is_product_category(['first', 'second'])
            echo 'this is shop page';
        }
    }
});
