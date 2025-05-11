<?php

/**
 * Updates a FluentCRM custom field for all subscribers when triggered via URL (?wpr_fluentcrm_fields_update=1).
 * Edit $field and $value to change the custom field and value; requires admin access.
 */

add_action('admin_init', function () {
  if (isset($_GET['wpr_fluentcrm_fields_update']) && current_user_can('manage_options')) {
    $field = 'admission_year'; // Change this for other fields
    $value = 2021; // Change this for other values
    FluentCrm\App\Models\Subscriber::chunk(100, function ($subscribers) use ($field, $value) {
      foreach ($subscribers as $subscriber) {
        FluentCrmApi('contacts')->createOrUpdate([
          'email' => $subscriber->email,
          'custom_values' => [$field => $value]
        ], true, false, false);
      }
    });
    wp_die('Custom field update completed.');
  }
});
