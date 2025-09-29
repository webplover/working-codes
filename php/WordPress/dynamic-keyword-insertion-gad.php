<?php
/**
 * Helper function: Get formatted utm_term (Title Case) or false if not set
 */
function wpr_get_formatted_utm_term() {
  if (isset($_GET['utm_term']) && !empty($_GET['utm_term'])) {
    $utm_term = sanitize_text_field($_GET['utm_term']);
    $utm_term = str_replace(['-', '_'], ' ', $utm_term); // Support dashes & underscores
    return ucwords(strtolower($utm_term)); // Capitalize each word
  }
  return false;
}

/**
 * 1. Parcel shortcode
 */
add_shortcode('wpr_your_shortcode_name', function () {
  $headline_content = "Get Your Parcel"; // Default text

  if ($utm_term = wpr_get_formatted_utm_term()) {
    $headline_content = esc_html($utm_term) . " in 60 Minutes.";
  }

  return $headline_content;
});

/**
 * 2. Home hero heading
 */
add_shortcode('wpr_home_hero_heading', function () {
  $headline_content = "Got a Flat Tyre? We'll Be There in 30-60 Minutes<span class='wpr-inner-text'></span>";

  if ($utm_term = wpr_get_formatted_utm_term()) {
    $headline_content = esc_html($utm_term) . ". We'll Be There in 30-60 Minutes<span class='wpr-inner-text'></span>";
  }

  return $headline_content;
});

/**
 * 3. Contact page text
 */
add_shortcode('wpr_contact_page_text_1', function () {
  $headline_content = "Contact Us"; // Default text

  if ($utm_term = wpr_get_formatted_utm_term()) {
    $headline_content = 'Contact Us for ' . esc_html($utm_term) . '<span class="wpr_contact_page_text_1"></span>';
  }

  return $headline_content;
});
