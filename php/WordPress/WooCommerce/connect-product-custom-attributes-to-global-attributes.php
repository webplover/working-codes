
<?php

/**
 * Connect product custom attributes to global attributes
 */

add_action('admin_init', function () {

  $products = wc_get_products(array(
    'limit' => -1,
    'status' => 'publish',
    'type' => array('simple', 'variable')
  ));

  foreach ($products as $product) {
    $custom_attributes = $product->get_attributes();

    $new_attributes = array();
    $global_attributes_added = array();

    foreach ($custom_attributes as $attribute_name => $attribute) {
      // Skip global attributes
      if ($attribute->get_id()) {
        $new_attributes[$attribute_name] = $attribute;
        continue;
      }

      $taxonomy = wc_sanitize_taxonomy_name($attribute_name);

      // Trim the taxonomy name to 26 characters if its length is more than 26
      if (strlen($taxonomy) > 26) {
        $taxonomy = substr($taxonomy, 0, 26);
      }

      $taxonomy = 'pa_' . $taxonomy;

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
