<?php

// Check dki using this link
// /?utm_source=google-ads&utm_medium=cpc&utm_campaign=wpr_test&utm_term=This-is-DKI



// 1.

add_shortcode('wpr_your_shortcode_name', function () {
  $headline_content = "Get Your Parcel"; // Default text

  if (isset($_GET['utm_term']) && !empty($_GET['utm_term'])) {
    $utm_term = sanitize_text_field($_GET['utm_term']);
    $utm_term = str_replace('-', ' ', $utm_term);
    $headline_content = esc_html($utm_term) . " in 60 Minutes.";
  }

  return $headline_content;
});


// 2.

add_shortcode('wpr_home_hero_heading', function () {
  $headline_content = "Got a Flat Tyre? We'll Be There in 30-60 Minutes<span class='wpr-inner-text'></span>"; // Default text

  if (isset($_GET['utm_term']) && !empty($_GET['utm_term'])) {
    $utm_term = sanitize_text_field($_GET['utm_term']);
    $utm_term = str_replace('-', ' ', $utm_term);
    $headline_content = esc_html($utm_term) . ". We'll Be There in 30-60 Minutes<span class='wpr-inner-text'></span>";
  }

  return $headline_content;
});


// 3.

add_shortcode('wpr_contact_page_text_1', function () {
  $headline_content = "Contact Us"; // Default text

  if (isset($_GET['utm_term']) && !empty($_GET['utm_term'])) {
    $utm_term = sanitize_text_field($_GET['utm_term']);
    $utm_term = str_replace('-', ' ', $utm_term);
    $headline_content = 'Contact Us for ' . esc_html($utm_term) . '<span class="wpr_contact_page_text_1"></span>';
  }

  return $headline_content;
});
