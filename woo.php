<?php

/**
 * Get attribute terms and store them in array
 * by default it will hide empty terms, if you want to get all terms use the below code
 * get_terms(['taxonomy' => 'pa_name', 'hide_empty' => false])
 */


foreach (get_terms('pa_name') as $term) {
    $arr[] = $term->name;
}


/**
 * Register an attribute taxonomy
 */


add_action(
    'admin_init',
    function () {

        $attributes = wc_get_attribute_taxonomies();

        $slugs = wp_list_pluck($attributes, 'attribute_name');

        if (!in_array('my_color', $slugs)) {

            $args = array(
                'slug'    => 'my_color',
                'name'   => __('My Color', 'your-textdomain'),
                'type'    => 'select',
                'orderby' => 'menu_order',
                'has_archives'  => false,
            );

            wc_create_attribute($args);
        }
    }
);

/**
 * Add term to existing attribute
 */

add_action('admin_init', function () {
    wp_insert_term('term_name', 'pa_my_color');
});


/**
 * Check if product has specific attribute
 */

$product = wc_get_product($product_id);

$color = $product->get_attribute('pa_color');

if (!empty($color)) {
} else {
}

/**
 * Add attribute to product
 */

add_action('admin_init', function () {


    $product_id = '11';
    $attribute_name = 'pa_size';
    $attribute_value = 'small';

    // It will tegister the attribute term if not exists already
    wp_set_object_terms($product_id, $attribute_value, $attribute_name, true);

    $data = [
        $attribute_name => [
            'name' => $attribute_name,
            'value' => $attribute_value,
            'is_visible' => '1',
            'is_variation' => '0',
            'is_taxonomy' => '1'
        ]
    ];
    //First getting the Post Meta
    $_product_attributes = get_post_meta($product_id, '_product_attributes', TRUE);
    //Updating the Post Meta
    update_post_meta($product_id, '_product_attributes', array_merge($_product_attributes, $data));
});

/**
 * If woocommerce activated, and also if shop or specefic category page
 */

add_action('wp_footer', function () {
    if (class_exists('woocommerce')) {
        if (is_shop() || is_product_category('category_slug')) { // Or pass array for multiple categories is_product_category(['first', 'second'])
            echo 'this is shop page';
        }
    }
});



/**
 * 
 */
