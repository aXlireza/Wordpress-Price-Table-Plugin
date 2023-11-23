<?php

// This function checks which category settings are true (selected)
// function get_selected_home_page_categories() {
//     $selected_categories = [];
//     $parent_categories = get_price_table_parent_categories(); // Retrieve the list of parent categories

//     foreach ($parent_categories as $id => $name) {
//         $setting_id = 'home_page_category_' . $id;
//         if (get_theme_mod($setting_id)) {
//             // If the setting is true, add the category ID to the selected categories array
//             $selected_categories[] = $id;
//         }
//     }

//     return $selected_categories;
// }

add_shortcode('home_page_lowest_price_posts', 'display_lowest_price_posts');
function display_lowest_price_posts() {
    include plugin_dir_path(__FILE__) . '../templates/template-parts/othercategories.php';
    // Start output buffering
    ob_start();

    // Now, you can use this function to get the array of selected category IDs
    $selected_category_ids = get_selected_home_page_categories();

    // Output the list of selected categories
    $selected_categories_data = [];
    foreach ($selected_category_ids as $category_id) {
        $category = get_term($category_id, 'price_table_category');

        $category_data = [
            'id' => $category->term_id,
            'name' => $category->name,
            // 'link' => get_term_link($category), // Get the link to the subcategory
            'link' => "#".$category->term_id, // Get the link to the subcategory
            'thumbnail_src' => get_term_meta($category_id, 'category_thumbnail_src', true), // Thumbnail source for the subcategory
        ];
        $selected_categories_data[] = $category_data;
    }

    // Output the table for each category
    echo '<div id="homepage-table-price" class="table-container" style="max-width: unset!important;">';
    price_cat_list($selected_categories_data);
    echo '<div class="table-header">';
    // Add your table headers here
    echo '
        <span>عنوان</span>
        <span>سایز</span>
        <span style="text-align: center;">کارخانه</span>
        <span style="text-align: center;">وزن</span>
        <span>قیمت و نوسان</span>
        <span style="text-align: center;">سایر</span>
    ';
    echo '</div>';
    foreach ($selected_category_ids as $category_id) {
        $args = array(
            'post_type'      => 'price_table',
            'posts_per_page' => 5,
            'tax_query'      => array(
                array(
                    'taxonomy' => 'price_table_category',
                    'field'    => 'term_id',
                    'terms'    => $category_id,
                ),
            ),
            'meta_key'       => 'current_price',
            'orderby'        => 'meta_value_num',
            'order'          => 'ASC',
        );
        $query = new WP_Query($args);

        echo "<section class=\"home_price_table_rows hidden\" id=\"table$category_id\">";
        if ($query->have_posts()) {
            
            while ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();
                $factory_name = wp_get_post_terms($post_id, 'price_table_factory') ? wp_get_post_terms($post_id, 'price_table_factory')[0]->name : '';
                $factory_id = wp_get_post_terms($post_id, 'price_table_factory') ? wp_get_post_terms($post_id, 'price_table_factory')[0]->term_id : '';
                $your_select_field_value_value = get_post_custom_values("_thickness", $post_id) ? get_post_custom_values("_thickness", $post_id)[0] : '';
                $alloy = get_post_custom_values('_alloy', $post_id) ? get_post_custom_values('_alloy', $post_id)[0] : '';
                $unit = get_post_custom_values('_unit', $post_id) ? get_post_custom_values('_unit', $post_id)[0] : '';
                $weight = get_post_custom_values("_weight", $post_id) ? get_post_custom_values("_weight", $post_id)[0] : '0';
                $cta = get_post_custom_values('_cta', $post_id) ? get_post_custom_values('_cta', $post_id)[0] : 'off';
                $price = get_post_custom_values('current_price', $post_id) ? get_post_custom_values('current_price', $post_id)[0] : 0;
                $price_change = get_post_custom_values('price_change', $post_id) ? get_post_custom_values('price_change', $post_id)[0] : 0;
                $price_history = template_price_history($post_id);
                $post_modified = get_post_modified_time('Y/m/d');
                
                $change_sign = substr($price_change, 0, 1) == '-' ? '-' : '+';
                if (isset($cta) && $cta == 'on') $change_sign = 'call';
                elseif (substr($price_change, 0, 1) == '0') $change_sign = '';
                elseif (substr($price_change, 0, 1) == '-') $change_sign = '-';
                else $change_sign = '+';
                
                $link = get_the_permalink($post_id);
                $title = get_the_title($post_id);
                $changes = $price_change;

                $change_sign_class_name = 'neutral';
                if ($change_sign == '+') $change_sign_class_name = 'up';
                elseif ($change_sign == '-') $change_sign_class_name = 'down';
                elseif ($change_sign == 'call') {
                    $change_sign_class_name = 'phone';
                    $change_sign = '';
                    $changes = '';
                }
            
                // deformat the data to pass to js function
                $price_history_dates = json_encode($price_history[1]);
                $price_history_dates = substr($price_history_dates, 1, strlen($price_history_dates)-2);
                if (strlen($price_history_dates) == 0) $price_history_dates = null;
                $price_history_dates = str_replace("\"", "'", $price_history_dates);
                $price_history_dates = str_replace(",", "_", $price_history_dates);
                $price_history_values = json_encode($price_history[0]);
                $price_history_values = substr($price_history_values, 1, strlen($price_history_values)-2);
                if (strlen($price_history_values) == 0) $price_history_values = null;
                $price_history_values = str_replace("\"", "'", $price_history_values);
                $price_history_values = str_replace(",", "_", $price_history_values);
            
                echo "<div class=\"info_row_container\">
                    <div id=\"$post_id\" class=\"info-row rtl\">
                        <div class=\"stock-title\">
                            <a href=\"$link\" class=\"title-main farsi-numbers\">$title</a>
                        </div>
                        <div class=\"row-size farsi-numbers\">$your_select_field_value_value</div>
                        <div class=\"row-size\">$factory_name</div>
                        <div class=\"row-size\">$weight کیلوگرم</div>
                        <div class=\"price-information $change_sign_class_name\">
                            <div class=\"price\">
                                <span class=\"farsi-numbers pricenumber\" original_price=\"$price\">$price</span>
                                <span class=\"currency\">تومان</span>
                            </div>
                            <div class=\"status-box\">
                                <span class=\"arrow-icon fa-icon\"></span>
                                <span class=\"status-number farsi-numbers\">$changes</span>
                            </div>
                        </div>
                        <a href=\"$link\" class=\"description-label\">توضیحات</a>
                    </div>
                </div>";
            }
        }
        echo "</section>";
        wp_reset_postdata();
    }
    echo '</div>'; // Close table-container

    // End output buffering and return the buffer content
    return ob_get_clean();
}
