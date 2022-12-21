UPDATE `wp_posts` SET `post_status` = 'publish' WHERE `wp_posts`.`post_status` = 'draft' and `wp_posts`.`post_type` = 'cities_data';
