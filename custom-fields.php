<?php

// Add custom fields for thickness, size, weight, unit and price
add_action('add_meta_boxes', 'custom_price_table_fields_meta_box');
function custom_price_table_fields_meta_box() {
    add_meta_box(
        'custom_price_table_fields',
        'Price Table Details',
        'custom_price_table_fields_callback',
        'price_table',
        'normal',
        'high'
    );
}

function custom_price_table_fields_callback($post) {
    $thickness = get_post_meta($post->ID, '_thickness', true);
    $size = get_post_meta($post->ID, '_size', true);
    $alloy = get_post_meta($post->ID, '_alloy', true);
    $unit = get_post_meta($post->ID, '_unit', true);
    $weight = get_post_meta($post->ID, '_weight', true);
    $price = get_post_meta($post->ID, '_price', true);
    $cta_checked = get_post_meta($post->ID, '_cta', true);

    echo '<label for="thickness">ضخامت(mm)</label>';
    echo '<input type="text" id="thickness" name="thickness" value="' . esc_attr($thickness) . '"><br>';

    echo '<label for="size">ابعاد(cm)</label>';
    echo '<input type="text" id="size" name="size" value="' . esc_attr($size) . '"><br>';

    echo '<label for="alloy">آلیاژ</label>';
    echo '<input type="text" id="alloy" name="alloy" value="' . esc_attr($alloy) . '"><br>';

    echo '<label for="unit">واحد</label>';
    echo '<input type="text" id="unit" name="unit" value="' . esc_attr($unit) . '"><br>';

    echo '<label for="unit">وزن</label>';
    echo '<input type="text" id="weight" name="weight" value="' . esc_attr($weight) . '"><br>';

    echo '<label for="cta">کاربر تماس بگیرد؟:</label>';
    echo '<input type="checkbox" id="cta" name="cta" ' . checked($cta_checked, 'on', false) . '>';
}

add_action('save_post', 'save_custom_price_table_fields');
function save_custom_price_table_fields($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (!current_user_can('edit_post', $post_id)) return;

    $fields = array('thickness', 'size', 'alloy', 'unit', 'weight');

    foreach ($fields as $field)
        if (isset($_POST[$field])) update_post_meta(
            $post_id, '_' . $field,
            sanitize_text_field($_POST[$field])
        );
    
    if (isset($_POST['price'])) update_price_and_history($post_id, sanitize_text_field($_POST['price']));

    if (isset($_POST['cta'])) update_post_meta(
        $post_id,
        '_cta',
        'on'
    );
    else delete_post_meta(
        $post_id,
        '_cta'
    );
}