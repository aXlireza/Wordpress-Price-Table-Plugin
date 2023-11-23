<?php
// Custom Taxonomy for Tags
add_action('init', 'custom_price_table_tags_taxonomy');
function custom_price_table_tags_taxonomy() {
    $labels = array(
        'name'              => 'ویژگی ها',
        'singular_name'     => 'ویژگی',
        'menu_name'         => 'ویژگی ها',
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'custom-tags'),
    );

    register_taxonomy('price_table_custom_tags', array('price_table'), $args);
}

// Custom Taxonomy for Category
add_action('init', 'custom_price_table_categories_taxonomy');
function custom_price_table_categories_taxonomy() {
    $labels = array(
        'name'              => 'دسته بندی ها',
        'singular_name'     => 'دسته بندی',
        'menu_name'         => 'دسته بندی ها',
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'price_categories'),
    );

    register_taxonomy('price_table_category', array('price_table'), $args);
}

// Custom Taxonomy for Factory
add_action('init', 'custom_price_table_factory_taxonomy');
function custom_price_table_factory_taxonomy() {
    $labels = array(
        'name'              => 'کارخانه ها',
        'singular_name'     => 'کارخانه',
        'menu_name'         => 'کارخانه ها',
    );
    
    $args = array(
        'labels'            => $labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'price_factory'),
    );
    
    register_taxonomy('price_table_factory', array('price_table'), $args);
}