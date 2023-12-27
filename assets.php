<?php

function enqueue_custom_styles() {
  wp_enqueue_style('pricetable-icons', plugin_dir_url(__FILE__) . 'assets/css/fontawesome.css');
  wp_enqueue_style('pricetable-fonts', plugin_dir_url(__FILE__) . 'assets/css/webfonts/font.css');
  wp_enqueue_style('pricetable-general', plugin_dir_url(__FILE__) . 'assets/css/general.css');
  wp_enqueue_style('pricetable-table-row', plugin_dir_url(__FILE__) . 'assets/css/tablerow.css');
  wp_enqueue_style('pricetable-table-head', plugin_dir_url(__FILE__) . 'assets/css/tablehead.css');
  wp_enqueue_style('pricetable-table-info', plugin_dir_url(__FILE__) . 'assets/css/tableinfo.css');
  wp_enqueue_style('pricetable-table-sidebar', plugin_dir_url(__FILE__) . 'assets/css/tablefiltersidebar.css');
  wp_enqueue_style('pricetable-table-categories', plugin_dir_url(__FILE__) . 'assets/css/categoryitems.css');
  wp_enqueue_style('pricetable-table-rate', plugin_dir_url(__FILE__) . 'assets/css/tablerate.css');
  wp_enqueue_style('pricetable-chart-popup', plugin_dir_url(__FILE__) . 'assets/css/popup.css');
  wp_enqueue_style('pricetable-single-post', plugin_dir_url(__FILE__) . 'assets/css/single-post.css');
  wp_enqueue_style('pricetable-home-tablerow', plugin_dir_url(__FILE__) . 'assets/css/home-tablerow.css');
  wp_enqueue_style('pricetable-accordion', plugin_dir_url(__FILE__) . 'assets/css/tablerow-accordion.css');
}
add_action('wp_enqueue_scripts', 'enqueue_custom_styles');


function enqueue_custom_scripts() {
  wp_enqueue_script('pricetable-main', plugin_dir_url(__FILE__) . 'assets/js/main.js', array(), '1.0', true);
  wp_enqueue_script('pricetable-chart-lib', 'https://cdn.jsdelivr.net/npm/chart.js', array(), '2.9.4', true);
  wp_enqueue_script('pricetable-html2canvas', 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js', array(), '1.3.2', true);
  wp_enqueue_script('pricetable-table-shot', plugin_dir_url(__FILE__) . 'assets/js/table-shot.js', array(), '1.1', true);
  wp_enqueue_script('pricetable-table-accordion', plugin_dir_url(__FILE__) . 'assets/js/table-accordion.js', array(), '1.1', true);
  wp_enqueue_script('pricetable-chart-js', plugin_dir_url(__FILE__) . 'assets/js/chart.js', array(), '1.0', true);
  wp_enqueue_script('pricetable-sidebar', plugin_dir_url(__FILE__) . 'assets/js/sidebar.js', array(), '1.1', true);
  wp_enqueue_script('pricetable-table-info', plugin_dir_url(__FILE__) . 'assets/js/table-info.js', array(), '1.0', true);
  wp_enqueue_script('pricetable-table-rate', plugin_dir_url(__FILE__) . 'assets/js/rate.js', array(), '1.0', true);
  wp_enqueue_script('pricetable-home-table', plugin_dir_url(__FILE__) . 'assets/js/home.js', array(), '1.0', true);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');


add_action('wp_head', 'add_custom_css_to_head');
function add_custom_css_to_head() {
    $stored_css = get_theme_mod('price_table_custom_css');
    if (!empty($stored_css)) {
        echo '<style type="text/css">' . $stored_css . '</style>';
    }
}