<?php

/*
Module Name: WPR Test
Version: 1.0

Description:
This module demonstrates hook integration in PerfexCRM, allowing testing of various hooks.

Instructions:
1. Create a folder named 'wpr_test' inside the 'modules' directory of PerfexCRM.
2. Place this file, 'wpr_test.php', inside the 'wpr_test' folder.
3. Modify the hook 'estimate_requests_created' to test other hooks available in PerfexCRM.

Usage:
Hooks into different events for testing purposes by replacing 'estimate_requests_created' with other hooks.

Logs:
Log file ('wpr_logfile.txt') will be stored in the root directory of PerfexCRM.
*/

hooks()->add_action('estimate_requests_created', function ($estimate_request_id) {

  $logMessage = 'estimate_request_id' . print_r($estimate_request_id, true) . "\n";

  file_put_contents('wpr_logfile.txt', $logMessage, FILE_APPEND);
});
