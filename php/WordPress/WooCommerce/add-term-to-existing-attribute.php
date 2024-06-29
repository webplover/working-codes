<?php

/**
 * Add term to existing attribute
 */

add_action('admin_init', function () {
    wp_insert_term('term_name', 'pa_my_color');
});
