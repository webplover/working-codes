<?php

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
