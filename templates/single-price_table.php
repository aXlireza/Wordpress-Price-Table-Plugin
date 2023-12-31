<?php

get_header();

while (have_posts()) : the_post();
    $post_id = get_the_ID();

    $terms = get_the_terms($post_id, 'price_table_category'); // Replace 'price_table_category' with your taxonomy

    $parent_term = null;
    $term_link = '';
    if (!empty($terms) && !is_wp_error($terms)) {
        // If you only expect one term per post, you'll take the first one
        // Otherwise, you'll need to determine which term you want to use
        $term = $terms[0];

        $term_link = get_term_link($term);
        // Loop to find the top-level parent term
        while ($term->parent != 0 && !is_wp_error($term)) {
            $term = get_term($term->parent, 'price_table_category');
        }

        // At this point, $term will be the top-level parent term
        $parent_term = $term;
    }

    $parent_term_name = $parent_term ? $parent_term->name : '';

    // // Now you can use $parent_term as the parent term object
    // if ($parent_term) {
    //     // For example, print the term name
    //     echo $parent_term->name;
    //     // Or get the term meta for the parent term
    //     $term_meta_value = get_term_meta($parent_term->term_id, 'your_meta_key', true);
    // }




    $factory_name = wp_get_post_terms($post_id, 'price_table_factory') ? wp_get_post_terms($post_id, 'price_table_factory')[0]->name : '';
    $factory_id = wp_get_post_terms($post_id, 'price_table_factory') ? wp_get_post_terms($post_id, 'price_table_factory')[0]->term_id : '';
    $sizeValue = get_post_custom_values("_size", $post_id)[0] ?? null;
    $thicknessValue = get_post_custom_values("_thickness", $post_id)[0] ?? '';
    
    $your_select_field_value_value = $sizeValue ?? $thicknessValue;
    $weight = get_post_custom_values("_weight", $post_id) ? get_post_custom_values("_weight", $post_id)[0] : '';
    $alloy = get_post_custom_values('_alloy', $post_id) ? get_post_custom_values('_alloy', $post_id)[0] : '';
    $unit = get_post_custom_values('_unit', $post_id) ? get_post_custom_values('_unit', $post_id)[0] : '';
    $cta = get_post_custom_values('_cta', $post_id) ? get_post_custom_values('_cta', $post_id)[0] : 'off';
    $price = get_post_custom_values('current_price', $post_id) ? get_post_custom_values('current_price', $post_id)[0] : 0;
    $price_change = get_post_custom_values('price_change', $post_id) ? get_post_custom_values('price_change', $post_id)[0] : 0;
    $price_history = template_price_history_rows($post_id);
    $post_modified = get_post_modified_time('Y/m/d');
    
    $change_sign = substr($price_change, 0, 1) == '-' ? '-' : '+';
    if (isset($cta) && $cta == 'on') $change_sign = 'call';
    elseif (substr($price_change, 0, 1) == '0') $change_sign = '';
    elseif (substr($price_change, 0, 1) == '-') $change_sign = '-';
    else $change_sign = '+';

    $price_history[0][2] = 0;
    for ($i = 1; $i < count($price_history); $i++) {
        $previous = str_replace(',', '', $price_history[$i - 1][0]);
        $current = str_replace(',', '', $price_history[$i][0]);
        $change = $current - $previous;
        $price_history[$i][2] = $change;
    }

    $price_history = array_reverse($price_history);


    // deformat the data to pass to js function
    $chart_price_history = template_price_history($post_id);
    $price_history_dates = json_encode($chart_price_history[1]);
    $price_history_dates = substr($price_history_dates, 1, strlen($price_history_dates)-2);
    if (strlen($price_history_dates) == 0) $price_history_dates = null;
    $price_history_dates = str_replace("\"", "'", $price_history_dates ?? '');
    $price_history_dates = str_replace("','", "_", $price_history_dates);
    $price_history_values = json_encode($chart_price_history[0]);
    $price_history_values = substr($price_history_values, 1, strlen($price_history_values)-2);
    if (strlen($price_history_values) == 0) $price_history_values = null;
    $price_history_values = str_replace("\"", "'", $price_history_values ?? '');
    $price_history_values = str_replace("','", "_", $price_history_values);

    $title = get_the_title();

    // Function to display breadcrumbs
    function custom_breadcrumbs() {
        $content = '';
        // Define breadcrumb separator
        $separator = ' &gt; '; // You can change the separator here
        
        // Get the queried object
        $queried_obj = get_queried_object();
        
        // Start the breadcrumbs output
        $content .= '<div class="breadcrumbs" dir="rtl">';
        
        // Home link
        $content .= '<a href="' . esc_url(home_url('/')) . '"> خانه </a>' . $separator;
        
        // Get the category of the single post
        $category = wp_get_post_terms($queried_obj->ID, 'price_table_category'); //get_the_category();
        $factory = wp_get_post_terms($queried_obj->ID, 'price_table_factory'); //get_the_category();
        if ($category) {
            $category_id = $category[0]->term_id;
            $content .= '<a href="' . esc_url(get_term_link($category_id)) . '">' . $category[0]->name . '</a>' . $separator;
        }
        if ($factory) {
            $category_id = $factory[0]->term_id;
            $content .= '<a href="' . esc_url(get_term_link($category_id)) . '">' . $factory[0]->name . '</a>' . $separator;
        }
        // Display current post title
        $content .= '<span class="current">' . get_the_title() . '</span>';
        
        // Close breadcrumbs div
        $content .= '</div>';
        return $content;
    }
    $breadcrumb = custom_breadcrumbs();
    $category_name = $terms[0]->name;
    echo "
    <nav dir='rtl' style='display: flex; flex-wrap: wrap; justify-content: space-between; margin-bottom: 20px;'>
        <h1 class='farsi-numbers' style='margin:0'>$title</h1>    
        $breadcrumb
    </nav>
    <div id=\"price_table_options_main\" class=\"single-page\">
        <section id=\"first_row_singlepage\">
            <div class=\"custom-table\">
                <div class=\"table-row\">
                    <div class=\"table-cell\">
                        <span class=\"cell-title\">تولید کننده:</span>
                        <a href=\"$term_link\" class=\"cell-value\">$factory_name</a>
                    </div>
                    <div class=\"table-cell\">
                        <span class=\"cell-title\">گروه:</span>
                        <span class=\"cell-value\">$category_name</span>
                    </div>
                </div>
                <div class=\"table-row\">
                    <div class=\"table-cell\">
                        <span class=\"cell-title\">وزن:</span>
                        <span class=\"cell-value farsi-numbers\">$weight</span>
                    </div>
                    <div class=\"table-cell\">
                        <span class=\"cell-title\">سایز:</span>
                        <span class=\"cell-value farsi-numbers\">$your_select_field_value_value</span>
                    </div>
                </div>
            </div>


            <div class=\"card-container\">
                <div class=\"card\">
                    <div class=\"card-header\">
                        <span>تاریخ</span>
                        <span>قیمت (تومان)</span>
                        <span>نوسان (تومان)</span>
                    </div>";
                    foreach ($price_history as $record) {
                        if (count($record) == 3) {
                            $change_sign = strval($record[2])[0] == '-' ? 'down' : 'up';
                            echo "<div class=\"card-content\">
                                <div class=\"date farsi-numbers\">$record[1]</div>
                                <div class=\"price farsi-numbers \">$record[0]</div>
                                <div dir='ltr' class=\"$change_sign amount farsi-numbers\">$record[2]</div>
                            </div>";
                        }
                    }
                echo "</div>
                <!-- Repeat the .card block for other entries -->
            </div>
        </section>
        <canvas id=\"pricetable_pricehistory_$post_id\"></canvas>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                chartpopup('$post_id', `$price_history_values`, `$price_history_dates`);
            });
        </script>
    </div>
    ";

endwhile;



get_footer();