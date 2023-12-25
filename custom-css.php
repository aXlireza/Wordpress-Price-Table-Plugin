<?php

add_action('customize_register', 'add_custom_css_customizer');
function add_custom_css_customizer($wp_customize) {
    $wp_customize->add_section('price_table_custom_css_section', array(
        'title' => 'Price Table Custom CSS',
        'priority' => 30,
    ));

    $wp_customize->add_setting('price_table_custom_css', array(
        'default' => '',
        'sanitize_callback' => 'wp_filter_nohtml_kses', // Sanitize the input
    ));

    $wp_customize->add_control('custom_css_control', array(
        'label' => 'Enter your custom CSS here:',
        'section' => 'price_table_custom_css_section',
        'settings' => 'price_table_custom_css',
        'type' => 'textarea',
    ));
}

add_action('customize_register', 'add_phonecall_number_customizer');
function add_phonecall_number_customizer($wp_customize) {
    $wp_customize->add_section('price_table_phonecall_tel_section', array(
        'title' => 'Price Table Phone call Tel',
        'priority' => 30,
    ));

    $wp_customize->add_setting('price_table_phonecall_tel', array(
        'default' => '',
        'sanitize_callback' => 'wp_filter_nohtml_kses', // Sanitize the input
    ));

    $wp_customize->add_control('custom_css_control', array(
        'label' => 'Enter The Phonecall Number here:',
        'section' => 'price_table_phonecall_tel_section',
        'settings' => 'price_table_phonecall_tel',
        'type' => 'text',
    ));
}