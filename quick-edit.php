<?php

// Add custom fields to Quick Edit
add_action('quick_edit_custom_box', 'add_custom_fields_to_quick_edit', 10, 2);
function add_custom_fields_to_quick_edit($column_name, $post_type) {
    if ($post_type === 'price_table' && $column_name === '_cta') {
        global $post;
        $current_cta = get_post_meta($post->ID, '_cta', true); // Get current _cta value

        echo '<fieldset class="inline-edit-col-right"><div class="inline-edit-col">';
        echo '<label class="alignleft"><span class="title">CTA</span>';
        echo '<select name="_cta" id="cta">';
        echo '<option value="">— No Change —</option>';

        // Check and set selected attribute for options
        echo '<option value="on" ' . selected('on', $current_cta, false) . '>On</option>';
        echo '<option value="off" ' . selected('off', $current_cta, false) . '>Off</option>';

        echo '</select></label>';
        echo '</div></fieldset>';
    }
}

// Save custom fields from Quick Edit
add_action('save_post', 'save_custom_fields_from_quick_edit');
function save_custom_fields_from_quick_edit($post_id) {
    if (isset($_REQUEST['_cta'])) {
        update_post_meta($post_id, '_cta', sanitize_text_field($_REQUEST['_cta']));
    }
}


// Add CTA column to the list of posts
add_filter('manage_price_table_posts_columns', 'add_cta_column');
function add_cta_column($columns) {
    $columns['_cta'] = 'CTA';
    return $columns;
}

// Display CTA value in the CTA column
add_action('manage_price_table_posts_custom_column', 'display_cta_column', 10, 2);
function display_cta_column($column, $post_id) {
    if ($column === '_cta') {
        $cta_value = get_post_meta($post_id, '_cta', true);
        echo esc_html($cta_value);
    }
}



// Add taxonomy filters above the post table
add_action('restrict_manage_posts', 'add_taxonomy_filters');
function add_taxonomy_filters() {
    global $typenow;

    // Check if we are on the correct post type
    if ($typenow === 'price_table') {
        $taxonomies = array('price_table_factory', 'price_table_custom_tags');

        foreach ($taxonomies as $taxonomy) {
            $selected = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
            $info_taxonomy = get_taxonomy($taxonomy);
            wp_dropdown_categories(array(
                'show_option_all' => __("Show All {$info_taxonomy->label}"),
                'taxonomy' => $taxonomy,
                'name' => $taxonomy,
                'orderby' => 'name',
                'selected' => $selected,
                'show_count' => true,
                'hide_empty' => true,
            ));
        }
    }
}

// Filter posts by taxonomy in the admin list
add_filter('parse_query', 'filter_posts_by_taxonomy');
function filter_posts_by_taxonomy($query) {
    global $pagenow;
    $type = 'price_table';
    $taxonomies = array('price_table_factory', 'price_table_custom_tags');

    if ($pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == $type && isset($_GET['taxonomy'])) {
        $taxonomy = $_GET['taxonomy'];
        $term_id = $_GET[$taxonomy];

        if (is_numeric($term_id)) {
            $term = get_term_by('id', $term_id, $taxonomy);
        } else {
            $term = get_term_by('slug', $term_id, $taxonomy);
        }

        if ($term && in_array($taxonomy, $taxonomies)) {
            $query->query_vars[$taxonomy] = $term->slug;
        }
    }
}
