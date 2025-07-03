<?php

/**
 * Shortcode to generate an E2Pdf download button using FluentCRM subscriber data,
 * as E2Pdf plugin does not natively support FluentCRM integration.
 * 
 * How to use:
 * In the E2Pdf template, use [e2pdf-argX] shortcodes
 * (e.g., [e2pdf-arg1], [e2pdf-arg2], etc.) to display subscriber data dynamically.
 * 
 * Required Plugin: https://wordpress.org/plugins/e2pdf/
 */

add_shortcode('e2pdf_fluentcrm_download', function ($atts) {
  // Define default attributes
  $atts = shortcode_atts(
    array(
      'page_id' => get_the_ID(),
      'subscriber_id' => 0,
      'template_id' => 1,
      'button_title' => 'Download PDF',
    ),
    $atts,
    'e2pdf_fluentcrm_download'
  );

  // Sanitize attributes
  $page_id = absint($atts['page_id']);
  $subscriber_id = absint($atts['subscriber_id']);
  $template_id = absint($atts['template_id']);
  $button_title = sanitize_text_field($atts['button_title']);

  // Check if subscriber_id is valid
  if ($subscriber_id <= 0) {
    return '<p>Error: Invalid subscriber ID.</p>';
  }

  // Get FluentCRM subscriber data
  $subscriber = \FluentCrm\App\Models\Subscriber::find($subscriber_id);
  if (!$subscriber) {
    return '<p>Error: Subscriber not found.</p>';
  }

  // Get subscriber and custom fields
  $main_fields = $subscriber->toArray();
  $custom_fields = $subscriber->custom_fields();
  $data = array_merge($main_fields, $custom_fields);

  $allowed_fields = [
    'arg1'  => 'full_name',
    'arg2'  => 'date_of_inspection',
    'arg3'  => 'vin',
    'arg4'  => 'make',
    'arg5'  => 'year',
    'arg6'  => 'model',
    'arg7'  => 'odometer_reading',
    'arg8'  => 'color',
    'arg9'  => 'no._of_cylinders',
    'arg10' => 'transmission_type',
    'arg11' => 'air_conditioning',
    'arg12' => 'cruise_control',
    'arg13' => 'power_seats',
    'arg14' => 'general_condition_of_vehi',
    'arg15' => 'power_steering',
    'arg16' => 'power_locks',
    'arg17' => 'cassette_cd_player',
    'arg18' => 'power_brakes',
    'arg19' => 'tilt_wheel',
    'arg20' => 'radio_am/fm',
    'arg21' => 'power_windows',
    'arg22' => 'two_door',
    'arg23' => 'four_door',
    'arg24' => 'general_comments',
    'arg25' => 'appraised_value',
    'arg26' => 'current_date',
  ];


  // Build the shortcode
  $shortcode = '[e2pdf-download'
    . ' id="' . esc_attr($template_id) . '"'
    . ' dataset="' . esc_attr($page_id) . '"'
    . ' button_title="' . esc_attr($button_title) . '"';

  // Add each arg even if value is missing
  foreach ($allowed_fields as $arg => $field_key) {
    $value = isset($data[$field_key]) ? $data[$field_key] : '';
    $shortcode .= ' ' . $arg . '="' . esc_attr($value) . '"';
  }

  $shortcode .= ']';

  // echo '<pre>';
  // print_r($shortcode);
  // echo '</pre>';

  return do_shortcode($shortcode);
});
