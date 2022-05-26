<?php

/**
 * Javascript Stuff
 */
?>

<script>
(function() {
let form_data = new FormData();
form_data.append("action", "my_action_name");
form_data.append("nonce", my_scripts.nonce);
form_data.append("additional_data", "some_data");

let response = await fetch(my_scripts.ajax_url, {
  method: "POST",
  processData: false,
  contentType: false,
  body: form_data,
});

let response_data = await response.json();
})();
</script>


<?php

/**
 * PHP Stuff
 */




/**
 * Add inline script
 */

wp_add_inline_script('enqueued-script-handle', 'const my_scripts = ' . json_encode([
  'ajax_url' => admin_url('admin-ajax.php'),
  'nonce' => wp_create_nonce('my-nonce'),
]), 'before');



/**
 * Handle ajax request
 */

add_action('wp_ajax_my_action_name', 'my_function');
add_action('wp_ajax_nopriv_my_action_name', 'my_function');


function my_function()
{
  if (!wp_verify_nonce($_REQUEST['nonce'], 'my-nonce')) {
    wp_send_json_error('Dont\t cheat us!');
  }



  // CURL sample

  $api_url = "https://something.com";

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $api_url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $output = curl_exec($ch);
  curl_close($ch);


  wp_send_json_success($output);
}
