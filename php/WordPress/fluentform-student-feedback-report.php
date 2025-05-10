<?php

/**
 * Add Menu Item
 */

add_action('admin_menu', function () {
  add_menu_page(
    '',                       // Page title (not used)
    'Student Feedback Report',          // Menu title (visible in the menu)
    'read',                   // Capability required to see the menu item
    'wpr-fluentform-feedback-report',   // Menu slug (unique identifier)
    null,                     // No callback function needed
    'dashicons-testimonial',     // Icon for the menu (optional)
    30                        // Position in the menu (optional)
  );

  // Override the menu link to point to the external URL
  global $menu;
  foreach ($menu as $key => $item) {
    if ($item[2] === 'wpr-fluentform-feedback-report') {
      $form_id = 4;
      $url = admin_url("/admin.php?wpr_view&page=fluent_forms&route=entries&form_id=$form_id#/visual_reports");
      $menu[$key][2] = 'javascript:window.open("' . $url . '", "_blank");';
      break;
    }
  }
});

/**
 * Add css and js
 */

add_action('admin_head', function () {
  if (
    is_admin() && isset($_GET['wpr_view'])
    && isset($_GET['page']) && $_GET['page'] === 'fluent_forms'
    && isset($_GET['route']) && $_GET['route'] === 'entries'
    && isset($_GET['form_id'])
  ) {

?>

    <style>
      #adminmenumain,
      #wpadminbar,
      #wpfooter,
      .form_internal_menu,
      .fluentform-admin-notice,
      #ff_form_entries_app>#print_view>div:first-child {
        display: none !important;
      }
    </style>

    <script>
      jQuery(document).ready(function($) {
        // Create the link element
        const $dashboardLink = $('<a>', {
          href: '<?php echo admin_url(); ?>',
          text: 'Back to Dashboard',
          css: {
            display: 'block',
            marginBottom: '20px',
            textDecoration: 'underline'
          }
        });

        // Find the container and prepend the link
        const $container = $('.ff_form_application_container');
        if ($container.length) {
          $container.prepend($dashboardLink);
        }
      });
    </script>

<?php
  }
});
