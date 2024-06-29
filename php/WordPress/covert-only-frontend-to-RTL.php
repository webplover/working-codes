<?php

add_filter('locale', function($locale) {
    if (!is_admin()) {
        return 'ur';
    }
    return $locale;
});
