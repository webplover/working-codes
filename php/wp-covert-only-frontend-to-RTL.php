<?php

add_filter('locale', function () {
     if (!is_admin()) {
          return 'ur';
     }
});
