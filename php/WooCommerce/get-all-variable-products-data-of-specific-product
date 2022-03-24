<?php
$product = wc_get_product( 83 ); // Change the product ID

if($product->is_type('variable')){
    foreach($product->get_available_variations() as $variation ){   
     
        // Prices
        $sale_price = floatval($variation['display_price']); // Sale price
        $regular_price = floatval($variation['display_regular_price']); // Regular Price
        
        echo '<strong>Regular Price</strong>: ' . $regular_price . '<br>';
        echo '<strong>Sale Price</strong>: ' . $sale_price . '<br><hr>';
    }
}
