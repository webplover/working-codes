<?php
$query = new WP_Query(['post_type' => 'product']);
if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
    }
}
wp_reset_query();
