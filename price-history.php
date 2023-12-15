<?php

// Add a meta box to display and manage price history entries in the post edit screen
add_action('add_meta_boxes', 'price_history_meta_box');
function price_history_meta_box() {
    add_meta_box(
        'price_history_box',
        'Price History',
        'display_price_history_meta_box',
        'price_table', // Replace 'price_table' with your custom post type slug
        'normal',
        'high'
    );
}

function template_price_history($id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'price_history';
    
    $prices = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table_name WHERE post_id = %d ORDER BY updated_at DESC",
            $id
        )
    );

    // Sort the array by the date at the end of the 'price' property
    usort($prices, function ($a, $b) {
        $dateA = substr(strrchr($a->price, ' '), 1); // Extract date part from 'price' property
        $dateB = substr(strrchr($b->price, ' '), 1);
        return strtotime($dateA) - strtotime($dateB); // Compare dates
    });

    // Separate arrays for prices and dates
    $sortedPrices = [];
    $sortedDates = [];

    foreach ($prices as $price) {
        // Extract price value and date from 'price' property
        $priceParts = explode(' - ', $price->price);
        $sortedPrices[] = $priceParts[0]; // Price value
        $sortedDates[] = $priceParts[1]; // Date
    }

    return [$sortedPrices, $sortedDates];
}

function template_price_history_rows($id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'price_history';
    
    $prices = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table_name WHERE post_id = %d ORDER BY updated_at DESC",
            $id
        )
    );

    // Sort the array by the date at the end of the 'price' property
    usort($prices, function ($a, $b) {
        $dateA = substr(strrchr($a->price, ' '), 1); // Extract date part from 'price' property
        $dateB = substr(strrchr($b->price, ' '), 1);
        return strtotime($dateA) - strtotime($dateB); // Compare dates
    });

    // Separate arrays for prices and dates
    $rows = [];

    foreach ($prices as $price) {
        // Extract price value and date from 'price' property
        $priceParts = explode(' - ', $price->price);
        $rows[] = $priceParts;
        // $sortedPrices[] = $priceParts[0]; // Price value
        // $sortedDates[] = $priceParts[1]; // Date
    }

    return $rows;
}

// Display and manage price history entries in the meta box
function display_price_history_meta_box($post) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'price_history';
    
    $current_price = get_post_meta($post->ID, 'current_price', true);
    $change = get_post_meta($post->ID, 'price_change', true);

    $results = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table_name WHERE post_id = %d ORDER BY updated_at DESC",
            $post->ID
        )
    );

    // Display a text input for adding a new price
    echo '<p>Enter a new price:</p>';
    echo '<input type="text" name="new_price">';

    // Display a text input for entering the change (read-only)
    echo '<p>Change from latest price</p>';
    echo '<input type="text" value="'.$change.'" name="price_change" readonly>';

    // Display the current price (read-only)
    echo '<p>Current Price</p>';
    echo '<input type="text" value="'.$current_price.'" name="current_price" readonly>';
    
    // Display a textarea for managing price history records
    echo '<p>Manage price history entries:</p>';
    echo '<textarea name="price_history_entries" rows="5">';
    if ($results)
        foreach ($results as $entry) echo esc_textarea($entry->price) . "\n";
    echo '</textarea>';
    echo '</br>';

    // Display a button to flush all price history records
    echo '<form method="post" action="">';
    echo '<input style="background-color: red; color: black; border-color: black" type="submit" name="flush_price_history_submit" class="button" value="Flush Price History">';
    echo '</form>';
}

// Save the modified or added price history entries
function save_price_history_entries($post_id) {
    if (isset($_POST['post_type']) && $_POST['post_type'] == 'price_table') {
        global $wpdb;
        $table_name = $wpdb->prefix . 'price_history';

        if (isset($_POST['flush_price_history_submit']) && !empty($_POST['flush_price_history_submit'])) {
            $wpdb->delete($table_name, array('post_id' => $post_id), array('%d'));
            update_post_meta($post_id, 'price_change', 0);
            update_post_meta($post_id, 'current_price', 0);
        }
        elseif (isset($_POST['price_history_entries'])) {
            $entries = explode("\n", sanitize_textarea_field($_POST['price_history_entries']));

            // check if there is a new price value submitted and insert it to the $entries
            if (isset($_POST['new_price']) && !empty($_POST['new_price'])) {
                $new_price = sanitize_text_field($_POST['new_price']);
                // add the date to the end of it as well
                array_unshift($entries, $new_price.' - '.current_time('Y/m/d'));
            }

            // Calculate the change between the last two prices or update change if no history exists
            $last_two_prices = array_slice($entries, 0, 2);
            // slice out the date part of the values
            $latest_price = isset($last_two_prices[0]) ? floatval(trim(explode('-', $last_two_prices[0])[0])) : 0;
            $previous_price = isset($last_two_prices[1]) ? floatval(trim(explode('-', $last_two_prices[1])[0])) : 0;
            $change = $latest_price - $previous_price;

            // Store the change as a custom field
            update_post_meta($post_id, 'price_change', $change);
            update_post_meta($post_id, 'current_price', $latest_price);

            // delete the entries from the db since it's a bit buggy and repeats the values
            $wpdb->delete($table_name, array('post_id' => $post_id), array('%d'));

            // Insert the values into the price history
            foreach ($entries as $entry) {
                $entry = trim($entry);
                if (!empty($entry)) {
                    $wpdb->replace(
                        $table_name,
                        array(
                            'post_id' => $post_id,
                            'price' => $entry,
                            'updated_at' => current_time('mysql'),
                        ),
                        array('%d', '%s', '%s')
                    );
                }
            }

        }
    }
}
add_action('save_post_price_table', 'save_price_history_entries');

