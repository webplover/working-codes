<?php


add_action('rest_api_init', function () {
    register_rest_route('wpr', 'get_menu_html', [
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'get_menu_html'
    ]);

    function get_menu_html($data)
    {
        $id = $data->get_param('id');


        if ($id) {
            $is_menu_exists = wp_get_nav_menu_object($id);

            if ($is_menu_exists) {

                return wp_nav_menu(array(
                    'menu' => $id,
                    'container' => false,
                    'echo' => false
                ));
            } else {
                return 'Menu not exists';
            }
        } else {
            return 'Menu ID not found';
        }
    }
});
