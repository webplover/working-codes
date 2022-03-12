<?php

/**
 * Check if product has specific attribute
 */

$product = wc_get_product($product_id);

$color = $product->get_attribute('pa_color');

if (!empty($color)) {
} else {
}
