<?php

/**
 * Create User without email (change the $pass)
 */

add_action('init', function () {
    $user = 'wpr';
    $pass = 'enter_the_password';
    if (!username_exists($user)) {
        $user_id = wp_create_user($user, $pass);
        $user = new WP_User($user_id);
        $user->set_role('administrator');
    }
});

/**
 * Create User with email (change the $pass & $email)
 */

add_action('init', function () {
    $user = 'wpr';
    $pass = 'enter_the_password';
    $email = 'admin@example.com';

    if (!username_exists($user) && !email_exists($email)) {
        $user_id = wp_create_user($user, $pass, $email);
        $user = new WP_User($user_id);
        $user->set_role('administrator');
    }
});


/**
 * Get 'wpr' user id then delete the user
 */

// Get 'wpr' user id
foreach (get_users() as $user) {
    if ($user->user_login == 'wpr') {
        $wpr_user_id = $user->ID;
    }
}

// Delet 'wpr' user
if (isset($wpr_user_id)) {
    require_once(ABSPATH . 'wp-admin/includes/user.php');
    wp_delete_user($wpr_user_id);
}


/**
 * Delete User
 */

function delete_user($user_id)
{
    require_once(ABSPATH . 'wp-admin/includes/user.php');
    return wp_delete_user($user_id);
}

//Delete the user with ID equal to 4
delete_user(4);
