<?php

/**
 * Javascript Stuff
 */
?>

<script>
  (async function() {

    const data = {
      action: 'my_action_name',
      security: my_scripts.security,
      some_data: 'some_value'
    };

    try {
      const response = await fetch(my_scripts.ajax_url, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
      });

      const responseData = await response.text();
    } catch (error) {
      console.error('Error:', error);
    }


  })();
</script>


<?php

/**
 * PHP Stuff
 */




/**
 * Localize the script with new data
 */

wp_localize_script('enqueued-script-handle', 'my_scripts', array(
  'ajax_url' => admin_url('admin-ajax.php'),
  'security' => wp_create_nonce('my_nonce')
));


/**
 * Handle ajax request
 */

add_action('wp_ajax_my_action_name', 'my_function');
add_action('wp_ajax_nopriv_my_action_name', 'my_function');


function my_function()
{
  // Check the nonce for security
  check_ajax_referer('my_nonce', 'security');

  // Handle your request here
  $some_data = sanitize_text_field($_GET['some_data']); // $_GET or $_POST



  // CURL sample

  $api_url = "https://something.com";

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $api_url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $output = curl_exec($ch);
  curl_close($ch);


  wp_send_json_success($output);

  // Always die in WordPress AJAX functions
  wp_die();
}
