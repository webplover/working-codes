
$wpr_post_content = '';
function wpr_process_blocks($blocks)
{
    global $wpr_post_content;
    foreach ($blocks as $block) {
        if ($block['blockName'] === 'core/shortcode') {
            $wpr_post_content .= do_shortcode(render_block($block));
        } else if (!empty($block['innerBlocks'])) {
            wpr_process_blocks($block['innerBlocks']);
        } else {
            $wpr_post_content .= render_block($block);
        }
    }
}


add_shortcode('my_content', function () {

    global $wpr_post_content;

    $blocks = parse_blocks(get_the_content());
    wpr_process_blocks($blocks);

    return $wpr_post_content;
});
