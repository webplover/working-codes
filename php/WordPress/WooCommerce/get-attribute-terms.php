<?php

/**
 * Get attribute terms and store them in array
 * by default it will hide empty terms, if you want to get all terms use the below code
 * get_terms(['taxonomy' => 'pa_name', 'hide_empty' => false])
 */


foreach (get_terms('pa_name') as $term) {
    $arr[] = $term->name;
}
