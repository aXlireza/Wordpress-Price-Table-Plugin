<?php
/**
 * price-table
 *
 * @package       priceTable
 * @author        aXlireza
 *
 * @wordpress-plugin
 * Plugin Name:       Price Table
 */

//  setup db price_history table
register_activation_hook(__FILE__, 'create_price_history_table');
function create_price_history_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'price_history';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        post_id mediumint(9) NOT NULL,
        price varchar(20) NOT NULL,
        updated_at datetime NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// CSS Custom Settings init
function my_plugin_settings_page() {
    add_options_page(
        'My Plugin Settings', // Page Title
        'My Plugin Settings', // Menu Title
        'manage_options', // Capability
        'my-plugin-settings', // Menu Slug
        'my_plugin_settings_content' // Callback function to display content
    );
}
add_action('admin_menu', 'my_plugin_settings_page');
function my_plugin_settings_content() {
    ?>
    <div class="wrap">
        <h1>My Plugin Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('my-plugin-settings-group'); ?>
            <?php do_settings_sections('my-plugin-settings'); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
function my_plugin_settings_init() {
    register_setting(
        'my-plugin-settings-group', // Option Group
        'custom_css' // Option Name
    );

    add_settings_section(
        'custom_css_section', // Section ID
        'Custom CSS', // Section Title
        'my_plugin_custom_css_section_callback', // Callback function to display section content
        'my-plugin-settings' // Page Slug
    );

    add_settings_field(
        'custom_css_field', // Field ID
        'Enter CSS here', // Field Title
        'my_plugin_custom_css_field_callback', // Callback function to display field content
        'my-plugin-settings', // Page Slug
        'custom_css_section' // Section ID
    );
}
add_action('admin_init', 'my_plugin_settings_init');
function my_plugin_custom_css_section_callback() {
    echo 'Enter your custom CSS below:';
}

function my_plugin_custom_css_field_callback() {
    $custom_css = get_option('custom_css');
    ?>
    <textarea id="custom_css" name="custom_css" rows="8" cols="50"><?php echo esc_textarea($custom_css); ?></textarea>
    <?php
}




include plugin_dir_path(__FILE__) . 'price-history.php';
include plugin_dir_path(__FILE__) . 'categories-customfields.php';
include plugin_dir_path(__FILE__) . 'assets.php';
include plugin_dir_path(__FILE__) . 'templates/shortcode-price_table_category.php';

// Register Custom Post Type
add_action('init', 'custom_price_table_post_type');
function custom_price_table_post_type() {
    $labels = array(
        'name'                  => 'جدول قیمت',
        'singular_name'         => 'جدول قیمت',
        'menu_name'             => 'جدول قیمت',
        'add_new'               => 'افزودن',
        'add_new_item'          => 'افزودن جدول قیمت',
        'edit_item'             => 'تغییر جدول قیمت',
        'new_item'              => 'افزودن جدول قیمت',
        'view_item'             => 'دیدن جدول قیمت',
        'view_items'            => 'دیدن جدول قیمت',
        'search_items'          => 'جستجوی جدول قیمت',
        'not_found'             => 'هیچ جدول قیمتی پیدا نشد',
        'not_found_in_trash'    => 'هیچ جدول قیمتی در سطل زباله پیدا نشد',
    );
    
    $args = array(
        'label'                 => 'جدول قیمت',
        'description'           => 'محصولات مرتبط با جدول قیمت',
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'custom-fields', 'thumbnail'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-cart',
        'show_in_nav_menus'     => true,
        'publicly_queryable'    => true,
        'exclude_from_search'   => false,
        'has_archive'           => true,
        'capability_type'       => 'post',
        'taxonomies'            => array('price_table_category', 'price_table_factory'), // Add category and factory taxonomies
    );
    
    register_post_type('price_table', $args);
}

include plugin_dir_path(__FILE__) . 'taxonomies.php';
include plugin_dir_path(__FILE__) . 'custom-fields.php';
include plugin_dir_path(__FILE__) . 'quick-edit.php';
include plugin_dir_path(__FILE__) . 'quick-upload.php';
include plugin_dir_path(__FILE__) . 'home/shortcode.php';
include plugin_dir_path(__FILE__) . 'home/custom-fields.php';

add_filter('template_include', 'custom_price_table_category_template');
function custom_price_table_category_template($template) {
    if (is_tax('price_table_category')) {
        $custom_template = plugin_dir_path(__FILE__) . 'templates/taxonomy-price_table_category.php';
        return $custom_template;
    }
    return $template;
}

add_filter('template_include', 'custom_price_table_factory_template');
function custom_price_table_factory_template($template) {
    if (is_tax('price_table_factory')) {
        $custom_template = plugin_dir_path(__FILE__) . 'templates/taxonomy-price_table_factory.php';
        return $custom_template;
    }
    return $template;
}

add_filter('template_include', 'my_custom_post_type_template', 99);
function my_custom_post_type_template($template) {
    global $post;

    if ('price_table' === $post->post_type && is_single()) {
        // Path to the template file in your plugin folder
        $plugin_template = plugin_dir_path(__FILE__) . 'templates/single-price_table.php';

        // Check if the template file exists
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
    }

    return $template;
}
