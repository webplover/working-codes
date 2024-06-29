<?php

/**
 * WordPress filter to modify the email headers used by wp_mail().
 * This function replaces any existing Reply-To header with the administration email address from the site settings.
 * It ensures that all outgoing emails from WordPress use the admin email as the Reply-To address, which can help in centralizing email responses.
 * This filter is added with a high priority to ensure it executes last and the changes persist even after other plugins/themes might modify the email headers.
 */

add_filter(
  'wp_mail',
  function ($args) {
    $reply_to = 'Reply-To: ' . get_option('admin_email');

    if (!empty($args['headers'])) {
      if (!is_array($args['headers'])) {
        // Convert headers string to an array
        $args['headers'] = array_filter(explode("\n", str_replace("\r\n", "\n", $args['headers'])));
      }

      // Remove any existing Reply-To headers
      $args['headers'] = array_filter($args['headers'], function ($header) {
        return strpos(strtolower($header), 'reply-to:') !== 0;
      });
    } else {
      $args['headers'] = [];
    }

    // Add the new Reply-To header
    $args['headers'][] = $reply_to;

    return $args;
  },
  PHP_INT_MAX // Ensures this runs last
);
