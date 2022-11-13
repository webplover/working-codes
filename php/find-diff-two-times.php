<?php

// find diff between two times, and then print
$date1 = "2007-03-24";
$date2 = "2009-06-26";

$diff = abs(strtotime($date2) - strtotime($date1));
echo date('F j, Y', $diff);
