<?php

add_filter('locale', function($locale) {
    if (!is_admin()) {
        return 'ar';
    }
    return $locale;
});
