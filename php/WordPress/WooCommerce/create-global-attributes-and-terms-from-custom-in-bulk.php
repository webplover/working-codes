<?php

/**
 * Function to create global attributes and terms from custom attributes in WooCommerce.
 * 
 * if you want to increase limit of attribute slug, edit woocommerce\includes\wc-attribute-functions.php
 * line 'if (strlen($slug) > 28) {' increase 28 to higher number
 */

add_action('admin_init', function () {
  // Get all products
  $args = array(
    'post_type'      => 'product',
    'posts_per_page' => -1,
  );

  $products = get_posts($args);

  // Array to hold unique custom attributes and their terms
  $custom_attributes = array();

  // Loop through each product to get custom attributes and terms
  foreach ($products as $product_post) {
    $product_id = $product_post->ID;
    $product = wc_get_product($product_id);
    $attributes = $product->get_attributes();

    foreach ($attributes as $attribute) {
      if ($attribute->is_taxonomy()) {
        continue;
      }

      $attribute_name = $attribute->get_name();
      if (!isset($custom_attributes[$attribute_name])) {
        $custom_attributes[$attribute_name] = array();
      }

      $terms = $attribute->get_options();



      if (!empty($terms)) {
        $custom_attributes[$attribute_name] = array_merge($custom_attributes[$attribute_name], $terms);
      }
    }
  }

  // Check if global attributes and terms already exist, if not create them
  foreach ($custom_attributes as $attribute_name => $terms) {


    $attribute_slug = wc_sanitize_taxonomy_name($attribute_name);

    // Check if the attribute already exists
    $attribute_id = wc_attribute_taxonomy_id_by_name($attribute_name);

    if (!$attribute_id) {
      // Add the attribute
      $attribute_id = wc_create_attribute(array(
        'name'         => $attribute_name,
        'slug'         => $attribute_slug,
        'type'         => 'select', // Change if necessary
        'order_by'     => 'menu_order',
        'has_archives' => false,
      ));
    }

    // Register the attribute as a taxonomy
    if (!taxonomy_exists($attribute_slug)) {

      register_taxonomy(
        $attribute_slug,
        apply_filters('woocommerce_taxonomy_objects_' . $attribute_slug, array('product')),
        apply_filters('woocommerce_taxonomy_args_' . $attribute_slug, array(
          'labels'            => array(
            'name'          => $attribute_name,
            'singular_name' => $attribute_name,
          ),
          'hierarchical'      => false,
          'show_ui'           => false,
          'query_var'         => true,
          'rewrite'           => false,
        ))
      );
    }

    // Add terms to the attribute
    $terms = array_unique($terms); // Remove duplicate terms
    foreach ($terms as $term_name) {

      if (!term_exists($term_name, 'pa_' . $attribute_slug)) {
        wp_insert_term($term_name, 'pa_' . $attribute_slug);
      }
    }
  }

  /**
   * converts custom product attributes to global attributes
   */


  $products = wc_get_products(array(
    'limit' => -1,
    'status' => 'publish',
    'type' => array('simple', 'variable')
  ));

  foreach ($products as $product) {
    $product_id = $product->get_id();
    $custom_attributes = $product->get_attributes();

    $new_attributes = array();
    $global_attributes_added = array();

    foreach ($custom_attributes as $attribute_name => $attribute) {
      // Skip global attributes
      if ($attribute->get_id()) {
        $new_attributes[$attribute_name] = $attribute;
        continue;
      }

      $taxonomy = 'pa_' . wc_sanitize_taxonomy_name($attribute_name);

      // Check if the global attribute exists
      if (taxonomy_exists($taxonomy)) {
        $custom_values = $attribute->get_options();
        $term_ids = array();

        foreach ($custom_values as $value) {
          $term = get_term_by('name', $value, $taxonomy);
          if ($term) {
            $term_ids[] = $term->term_id;
          } else {
            // If term doesn't exist, create it
            $new_term = wp_insert_term($value, $taxonomy);
            if (!is_wp_error($new_term)) {
              $term_ids[] = $new_term['term_id'];
            }
          }
        }

        // Add global attribute to new attributes list
        if (!isset($global_attributes_added[$taxonomy])) {
          $global_attributes_added[$taxonomy] = new WC_Product_Attribute();
          $global_attributes_added[$taxonomy]->set_id(wc_attribute_taxonomy_id_by_name($taxonomy));
          $global_attributes_added[$taxonomy]->set_name($taxonomy);
          $global_attributes_added[$taxonomy]->set_options($term_ids);
          $global_attributes_added[$taxonomy]->set_position(count($new_attributes));
          $global_attributes_added[$taxonomy]->set_visible(true);
          $global_attributes_added[$taxonomy]->set_variation(false);
        } else {
          $existing_term_ids = $global_attributes_added[$taxonomy]->get_options();
          $global_attributes_added[$taxonomy]->set_options(array_merge($existing_term_ids, $term_ids));
        }
      } else {
        $new_attributes[$attribute_name] = $attribute;
      }
    }

    // Merge custom attributes with global attributes
    $new_attributes = array_merge($new_attributes, $global_attributes_added);

    // Save the product with the updated attributes
    $product->set_attributes($new_attributes);
    $product->save();
  }
});
