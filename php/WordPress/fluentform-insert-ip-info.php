<?php

/**
 * Before adding the following code to the site, ensure you create three hidden form fields
 * with the specific "Name Attributes": 'wpr_country', 'wpr_city', and 'wpr_ipinfo' and empty "Default Value".
 * 
 * These fields will automatically populate with the user's country, city, and detailed IP info 
 * (in JSON format) using the data fetched based on their IP address.
 * 
 * - 'wpr_country': This field will store the user's country.
 * - 'wpr_city': This field will store the user's city.
 * - 'wpr_ipinfo': This field will store the complete IP information in JSON format.
 */


/**
 * Get user ip
 */

function wpr_getVisIpAddr()
{

  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    return $_SERVER['HTTP_CLIENT_IP'];
  } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    return $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
    return $_SERVER['REMOTE_ADDR'];
  }
}

/**
 * Get user ipinfo
 */

function wpr_getVisIpAddr_info()
{

  $ip = wpr_getVisIpAddr();

  // $ipInfo = wp_remote_get("http://ip-api.com/json/$ip"); // Unlimited, without coutry_code info
  $ipInfo = wp_remote_get("http://ipwho.is/$ip"); // 10,000 requests per month, more data including coutry_code

  if (!is_wp_error($ipInfo)) {
    $body = wp_remote_retrieve_body($ipInfo);
    return json_decode($body, true);
  }

  return null;
}



/**
 * Fetch user info from user ip, and put in form fields
 */
add_filter(
  'fluentform/insert_response_data',
  function ($formData) {

    $ipData = wpr_getVisIpAddr_info();

    if ($ipData) {

      if (isset($formData['wpr_country'])) {
        $formData['wpr_country'] = $ipData['country'] ?? '';
      }

      if (isset($formData['wpr_city'])) {
        $formData['wpr_city'] = $ipData['city'] ?? '';
      }

      if (isset($formData['wpr_ipinfo'])) {
        $formData['wpr_ipinfo'] = json_encode($ipData, JSON_PRETTY_PRINT);
      }
    }

    return $formData;
  },
  10,
  2
);
