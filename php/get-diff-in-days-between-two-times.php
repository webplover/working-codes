<?php

// Get diff in days, between two dates

$date1 = 'July 21, 2022';
$date2 = date("F j, Y");

$diff = strtotime($date1) - strtotime($date2);
$oprator =  strtotime($date1) > strtotime($date2) ? '-' : '';
$days =  abs(round($diff / 86400));

echo "$oprator$days Days";
