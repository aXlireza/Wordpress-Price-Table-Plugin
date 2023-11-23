<?php

// Function to get parent categories from the custom taxonomy
function get_price_table_parent_categories() {
    $parents = get_terms(array(
        'taxonomy'   => 'price_table_category',
        'hide_empty' => false,
        'parent'     => 0,
    ));

    $choices = array();
    foreach ($parents as $parent) {
        $choices[$parent->term_id] = $parent->name;
    }
    return $choices;
}


add_action('customize_register', function ($wp_customize) {
    // Add a new section to the Customizer
    $wp_customize->add_section('home_page_settings', array(
        'title'    => __('Home Page Settings', 'text-domain'),
        'priority' => 30,
    ));

    // Get parent categories
    $parent_categories = get_price_table_parent_categories();
    
    // Add setting for each category
    foreach ($parent_categories as $id => $name) {
        $setting_id = 'home_page_category_' . $id;
        
        $wp_customize->add_setting($setting_id, array(
            'default' => false,
            'sanitize_callback' => 'wp_validate_boolean',
        ));

        $wp_customize->add_control($setting_id, array(
            'type' => 'checkbox',
            'section' => 'home_page_settings',
            'label' => $name,
        ));
    }
});

// Helper function to retrieve selected categories
function get_selected_home_page_categories() {
    $selected_categories = [];
    $parent_categories = get_price_table_parent_categories();
    foreach ($parent_categories as $id => $name) {
        if (get_theme_mod('home_page_category_' . $id, false)) {
            $selected_categories[] = $id;
        }
    }
    return $selected_categories;
}

