<?php
add_action('wp_head', function () { ?>
    <script type="text/javascript">
        const home_url = <?php echo json_encode(site_url()); ?>;
    </script><?php
});
