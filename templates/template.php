<?php
// Include the factory title component
include plugin_dir_path(__FILE__) . 'template-parts/table-row.php';
include plugin_dir_path(__FILE__) . 'template-parts/table-head.php';
include plugin_dir_path(__FILE__) . 'template-parts/table-info.php';
include plugin_dir_path(__FILE__) . 'template-parts/table-rate.php';
include plugin_dir_path(__FILE__) . 'template-parts/sidebar.php';
include plugin_dir_path(__FILE__) . 'template-parts/othercategories.php';

// Get the current category
$current_category = get_queried_object();
$current_category_id = $current_category->term_id;

// Initialize the variable to store your_select_field value
$your_select_field_value = '';
$your_select_field_value_fa = '';

// Check if the current category is a subcategory
if ($current_category->parent !== 0) {
    // It's a subcategory, so get the parent category ID
    $parent_category_id = $current_category->parent;

    // Fetch the your_select_field value from the parent category
    $your_select_field_value = get_term_meta($parent_category_id, 'your_select_field_meta_key', true);
    $your_select_field_value_fa = get_term_meta($parent_category_id, 'your_select_field_meta_key_fa', true);
} else {
    // It's not a subcategory, so fetch the value from the current category
    $your_select_field_value = get_term_meta($current_category_id, 'your_select_field_meta_key', true);
    $your_select_field_value_fa = get_term_meta($current_category_id, 'your_select_field_meta_key_fa', true);
}

// If the $your_select_field_value_fa is still empty, it'd be because the page is the factory taxonomy
if (empty($your_select_field_value_fa)) {
    $your_select_field_value_fa = "اندازه";
}

// Query posts from price_table post type sorted by price_table_factory taxonomy
$args = array(
    'post_type' => 'price_table',
    'tax_query' => array(
        array(
            'taxonomy' => $current_category->taxonomy,//'price_table_category', // Replace with your category taxonomy name
            'field' => 'slug',
            'terms' => $current_category->slug,
        ),
    ),
    'posts_per_page' => -1, // Display all posts from the category
    'orderby' => 'price_table_factory', // Sort by price_table_factory taxonomy
    'order' => 'ASC', // Change to 'DESC' for descending order
);

$posts_query = new WP_Query($args);

if ($posts_query->have_posts()) {
    $factory_posts = []; // Initialize array to hold posts grouped by factory

    while ($posts_query->have_posts()) {
        $posts_query->the_post();
        $factory = get_the_terms(get_the_ID(), 'price_table_factory'); // Get factory term(s)


        if ($factory && isset($factory[0])) {
            $factory_name = $factory[0]->name;
            $factory_link = get_term_link(get_term($factory[0]->term_id, 'price_table_factory'));
            $factory_id = $factory[0]->term_id;

            // Check if the factory key exists in the array, if not, create it
            if (!isset($factory_posts[$factory_name])) {
                $factory_posts[$factory_name] = [];
            }

            // Add the post to the respective factory key in the array
            $factory_posts[$factory_name]['id'] = $factory_id;
            $factory_posts[$factory_name]['title'] = $factory_name;
            $factory_posts[$factory_name]['link'] = $factory_link;
            $factory_posts[$factory_name]['posts'][] = get_post();
        }
    }
    wp_reset_postdata();

    // Get all subcategories of the current category or its parent category
    $subcategories = get_terms(array(
        'taxonomy' => 'price_table_category', // Replace with your category taxonomy name
        'parent' => ($current_category->parent !== 0) ? $current_category->parent : $current_category_id,
    ));

    $subcategories_array = []; // Initialize an array to hold subcategories

    // Loop through subcategories
    foreach ($subcategories as $subcategory) {
        // Retrieve thumbnail source for the current subcategory
        $thumbnail_src = get_term_meta($subcategory->term_id, 'category_thumbnail_src', true);

        // Store subcategory information in an array
        $subcategory_info = [
            'id' => $subcategory->term_id,
            'name' => $subcategory->name,
            'link' => get_term_link($subcategory), // Get the link to the subcategory
            'thumbnail_src' => $thumbnail_src, // Thumbnail source for the subcategory
        ];

        // Add the subcategory info to the subcategories array
        $subcategories_array[] = $subcategory_info;
    }
	echo "<section id=\"price_table_options_main\">";

    // Initialize an array to store sizes and their corresponding posts
    $sizes_list = [];

    foreach ($factory_posts as $factory_name => $factory_posts_array) {
        foreach ($factory_posts_array['posts'] as $post) {
            // Get the size for the current post
            $size = get_post_custom_values("_thickness", $post->ID) ? get_post_custom_values("_thickness", $post->ID)[0] : '';

            // Check if the size already exists in the sizes_list array
            if (!array_key_exists($size, $sizes_list)) {
                $sizes_list[$size] = [];
            }

            // Add the post ID (or any other post data you need) to the array for this size
            $sizes_list[$size][] = $post->ID; // You can store $post instead of $post->ID if you need the full post object
        }
    }


  	table_sidebar($factory_posts, array_keys($sizes_list), $your_select_field_value_fa);










    // ########## tables based on Factories
    echo "<section id=\"pricetable_mainbody_by_factory\">";
    price_cat_list($subcategories_array);
    table_rate();

    // Output or manipulate the $factory_posts array as needed
    foreach ($factory_posts as $factory_name => $factory_posts_array) {
        $factory_id = $factory_posts_array['id'];
        $screenshot_target_id = "element-to-capture$factory_id";
        echo "<div id=\"$screenshot_target_id\">";
        echo "<section class=\"factory_table\" id=\"$factory_id\">";
        // Display factory title
        $factory_id = 'factory_posts'.$factory_posts[$factory_name]['id'];
        $factory_list = [];
        foreach ($factory_posts as $again_factory_name)
            $factory_list[] = [$again_factory_name['title'], 'factory_posts'.$again_factory_name['id']];
        table_info($factory_name, $factory_id, $factory_posts[$factory_name]['link'], $factory_list, $current_category->name);
        table_head($your_select_field_value_fa, $screenshot_target_id);

        // Output or process posts for each factory
        echo '<div class="allrows">';
        foreach ($factory_posts_array['posts'] as $post)
            display_row($post->ID, $your_select_field_value, '', '');
        echo "</div>";
        echo "</section>";
        echo "</div>";
    }
    echo "</section>";






    


    // ########## tables based on Sizes
    echo "<section id=\"pricetable_mainbody_by_size\" class=\"hidden\">";
    price_cat_list($subcategories_array);
    table_rate_by_size();

    // Output or manipulate the $factory_posts array as needed
    // foreach ($factory_posts as $factory_name => $factory_posts_array) {
    foreach ($sizes_list as $thesize => $posts_ids) {
        $size_id = 'size'.str_replace('.', '_', $thesize);
        echo "<section class=\"size_table info_row_container\" id=\"$size_id\">";
        table_info_by_size($your_select_field_value_fa, $size_id, array_keys($sizes_list), $thesize);
        table_head_by_size($your_select_field_value_fa);

        // Output or process posts for each factory
        echo '<div class="allrows">';
        foreach ($posts_ids as $post_id)
            display_row($post_id, $your_select_field_value, 'factory_table', '');
        echo "</div>";
        echo "</section>";
    }
    echo "</section>";








	echo "</section>";
} else {
    echo '<p>No posts found.</p>';
}


function display_row($post_id, $your_select_field_value, $class, $customid) {
    $factory_name = wp_get_post_terms($post_id, 'price_table_factory')[0]->name;
    $factory_id = wp_get_post_terms($post_id, 'price_table_factory')[0]->term_id;
    $your_select_field_value_value = get_post_custom_values("_thickness", $post_id) ? get_post_custom_values("_thickness", $post_id)[0] : '';
    $alloy = get_post_custom_values('_alloy', $post_id) ? get_post_custom_values('_alloy', $post_id)[0] : '';
    $unit = get_post_custom_values('_unit', $post_id) ? get_post_custom_values('_unit', $post_id)[0] : '';
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

    table_row(
        $post_id,
        get_the_permalink($post_id),// $link,
        get_the_title($post_id),// $title,
        $factory_name,// $desc,
        $your_select_field_value_value,// $size,
        $alloy,// $weight,
        $unit,// $weight_unit,
        $post_modified,// $date,
        $price,// $price,
        $change_sign,
        $price_change,// $changes
        $price_history,
        '123456',
        $class,
        $factory_id,
    );
}