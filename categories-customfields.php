<?php

add_action('price_table_category_edit_form_fields', 'add_taxonomy_image_field', 10, 2);
function add_taxonomy_image_field($term, $taxonomy) {
    $thumbnail_src = get_term_meta($term->term_id, 'category_thumbnail_src', true);
    $select_value = get_term_meta($term->term_id, 'your_select_field_meta_key', true); // Replace with your meta key

    ?>
    <tr class="form-field">
        <th scope="row" valign="top">
            <label for="category-thumbnail-src"><?php esc_html_e('Thumbnail Source', 'text-domain'); ?></label>
        </th>
        <td>
            <input type="text" name="category_thumbnail_src" id="category-thumbnail-src" value="<?php echo esc_attr($thumbnail_src); ?>" style="width: 100%;" />
            <p class="description"><?php esc_html_e('Enter the URL for the category thumbnail.', 'text-domain'); ?></p>
        </td>
    </tr>
    <?php

    // Only render the select tag for parent categories
    if ($term->parent == 0) {
        // Add your select tag here
        ?>
        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="your-select-field"><?php esc_html_e('Select Field Label', 'text-domain'); ?></label>
            </th>
            <td>
                <select name="your_select_field" id="your-select-field">
                    <option value="thickness" <?php selected($select_value, 'thickness'); ?>>ضخامت</option>
                    <option value="size" <?php selected($select_value, 'size'); ?>>سایز</option>
                    <!-- Add more options as needed -->
                </select>
                <p class="description"><?php esc_html_e('Choose an option.', 'text-domain'); ?></p>
            </td>
        </tr>
    <?php
    }
}

add_action('edited_price_table_category', 'save_taxonomy_select_field', 10, 2);
function save_taxonomy_select_field($term_id, $tt_id) {
    if (isset($_POST['your_select_field'])) {
        $select_value = $_POST['your_select_field']; // Sanitize this value if necessary
        update_term_meta($term_id, 'your_select_field_meta_key', $select_value); // Replace with your meta key
        $the_fa = '';
        if ($select_value == 'thickness') $the_fa = 'ضخامت';
        elseif ($select_value == 'size') $the_fa = 'سایز';
        update_term_meta($term_id, 'your_select_field_meta_key_fa', $the_fa); // Replace with your meta key
    }
}

add_action('edited_price_table_category', 'save_taxonomy_image_field', 10, 2);
function save_taxonomy_image_field($term_id, $tt_id) {
    if (isset($_POST['category_thumbnail_src'])) {
        $thumbnail_src = wp_kses_post($_POST['category_thumbnail_src']);
        update_term_meta($term_id, 'category_thumbnail_src', $thumbnail_src);
    }
}
