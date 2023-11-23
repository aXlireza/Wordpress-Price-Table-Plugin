<?php
function custom_price_table_shortcode_function() {
    $output = '';

    $template_file = plugin_dir_path(__FILE__) . 'template.php';

    if (file_exists($template_file)) {
        ob_start();
        require_once $template_file;
        $output = ob_get_clean();
    } else {
        $output = 'Template file not found or unable to load.';
    }

    return $output;
}
add_shortcode('custom_price_table_shortcode', 'custom_price_table_shortcode_function');

?>
