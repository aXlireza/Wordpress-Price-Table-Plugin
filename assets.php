<?php

function enqueue_custom_styles() {
  // Define your custom post type(s)
  $custom_post_types = array('price_table'); // Replace with your custom post types
  // Check for single, taxonomy, or home page of the custom post types
  $is_custom_type_page = is_singular($custom_post_types) ||
                          is_post_type_archive($custom_post_types) ||
                          is_tax(get_object_taxonomies($custom_post_types));
  // Check if the current page is a singular of any of your custom post types or is the home page
  if ($is_custom_type_page || is_home() || is_front_page()) {
    wp_enqueue_style('pricetable-icons', plugin_dir_url(__FILE__) . 'assets/css/fontawesome.css');
    wp_enqueue_style('pricetable-fonts', plugin_dir_url(__FILE__) . 'assets/css/webfonts/font.css');
    // wp_enqueue_style('pricetable-general', plugin_dir_url(__FILE__) . 'assets/css/general.css');
    // wp_enqueue_style('pricetable-table-row', plugin_dir_url(__FILE__) . 'assets/css/tablerow.css');
    // wp_enqueue_style('pricetable-table-head', plugin_dir_url(__FILE__) . 'assets/css/tablehead.css');
    // wp_enqueue_style('pricetable-table-info', plugin_dir_url(__FILE__) . 'assets/css/tableinfo.css');
    // wp_enqueue_style('pricetable-table-sidebar', plugin_dir_url(__FILE__) . 'assets/css/tablefiltersidebar.css');
    // wp_enqueue_style('pricetable-table-categories', plugin_dir_url(__FILE__) . 'assets/css/categoryitems.css');
    // wp_enqueue_style('pricetable-table-rate', plugin_dir_url(__FILE__) . 'assets/css/tablerate.css');
    // wp_enqueue_style('pricetable-chart-popup', plugin_dir_url(__FILE__) . 'assets/css/popup.css');
    // wp_enqueue_style('pricetable-single-post', plugin_dir_url(__FILE__) . 'assets/css/single-post.css');
    // wp_enqueue_style('pricetable-home-tablerow', plugin_dir_url(__FILE__) . 'assets/css/home-tablerow.css');
    // wp_enqueue_style('pricetable-accordion', plugin_dir_url(__FILE__) . 'assets/css/tablerow-accordion.css');

    wp_enqueue_style('pricetable-css-bundle', plugin_dir_url(__FILE__) . 'assets/dist/bundle.css');
  }
}
add_action('wp_enqueue_scripts', 'enqueue_custom_styles');


add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');
function enqueue_custom_scripts() {
  // Define your custom post type(s)
  $custom_post_types = array('price_table'); // Replace with your custom post types
  // Check for single, taxonomy, or home page of the custom post types
  $is_custom_type_page = is_singular($custom_post_types) ||
                          is_post_type_archive($custom_post_types) ||
                          is_tax(get_object_taxonomies($custom_post_types));
  // Check if the current page is a singular of any of your custom post types or is the home page
  if ($is_custom_type_page || is_home() || is_front_page()) {
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

    // wp_enqueue_script('pricetable-js-bundle', plugin_dir_url(__FILE__) . 'assets/dist/bundle.js.bundle.js', array(), '1.0', true);
  }
}

function add_defer_attribute($tag, $handle) {
  // Array of script handles to defer
  $scripts_to_defer = array(
    'pricetable-chart-lib',
    'pricetable-html2canvas',
    'pricetable-table-shot',
    'pricetable-table-accordion',
    'pricetable-chart-js',
    'pricetable-sidebar',
    'pricetable-table-info',
    'pricetable-table-rate',
    'pricetable-home-table',
  );

  foreach($scripts_to_defer as $defer_script) {
      if ($defer_script === $handle) {
          return str_replace(' src', ' defer="defer" src', $tag);
      }
  }

  return $tag;
}
add_filter('script_loader_tag', 'add_defer_attribute', 10, 2);



add_action('wp_head', 'add_custom_css_to_head');
function add_custom_css_to_head() {
    $stored_css = get_theme_mod('price_table_custom_css');
    if (!empty($stored_css)) {
        echo '<style type="text/css">' . $stored_css . '</style>';
    }
}