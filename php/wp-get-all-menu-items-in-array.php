<?php
$menu_items = wp_get_nav_menu_items(1);


// Recursive function to build the menu array
function build_menu_array($items, $parent = 0)
{
    $menu_array = array();
    foreach ($items as $item) {
        if ($item->menu_item_parent == $parent) {
            $menu_array[] = [
                'icon' => get_post_meta($item->ID, '_kad_menu_icon_svg', true),
                'title' => $item->title,
                'url' => $item->url,
                'children' => build_menu_array($items, $item->ID),
            ];
        }
    }
    return $menu_array;
}


$final = build_menu_array($menu_items);
