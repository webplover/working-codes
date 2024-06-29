<?php
function get_all_child_pages($id)
{
    $pages = get_pages([
        'parent' => $id,
        'post_type' => 'docs',
        'sort_column' => 'menu_order'
    ]);
    $child_pages = [];

    foreach ($pages as $page) {
        $child_pages[] = [
            'id' => $page->ID,
            'title' => $page->post_title,
            'permalink' => get_permalink($page->ID),
            'children' => get_all_child_pages($page->ID)
        ];
    }

    return $child_pages;
}
