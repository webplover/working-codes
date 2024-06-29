<?php
add_shortcode('shortcode-with-params', function ($attr) {

    $args = shortcode_atts([
        'url' => '#'
    ], $attr);

    return $args['url'];
});
