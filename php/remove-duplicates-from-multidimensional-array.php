<?php

$arr = [
    'first' => [
        'a' => 'one',
        'b' => 'two',
        'c' => 'three',
    ],
    'second' => [
        'a' => 'one',
        'b' => 'two',
        'c' => 'three',
    ]
];

$arr = array_map("unserialize", array_unique(array_map("serialize", $arr)));
