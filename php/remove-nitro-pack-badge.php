<?php

// Remove NitroPack Footer Badge
add_action('wp_footer', function () {
  echo "<script>if(shadow){shadow.innerHTML=''}</script>";
});
