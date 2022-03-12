<?php

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
