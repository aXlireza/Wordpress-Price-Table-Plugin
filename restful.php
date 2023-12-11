<?php

// Register REST fields for the first set of custom fields
add_action('rest_api_init', 'register_custom_fields_rest');
function register_custom_fields_rest() {

  $fields = array(
    '_thickness',
    '_size',
    '_alloy',
    '_unit',
    '_weight',
    '_cta',
    'current_price',
    'price_change',
  );

  // Callbacks for list of custom fields
  foreach ($fields as $key) {
    register_rest_field('price_table', $key,
      array('update_callback' => 'update_field_rest')
    );
  }
}

add_action('rest_api_init', function () {
  register_rest_route('price-table/v1', '/price_history/', array(
      'methods' => 'POST',
      'callback' => 'insert_custom_data_callback',
  ));
});

function insert_custom_data_callback($request) {
  global $wpdb;

  $table_name = $wpdb->prefix . 'price_history';
  
  // Get data from the REST API request
  $post_id = $request->get_param('post_id');
  $entry = $request->get_param('entry');

  // Insert data into your custom table
  $wpdb->replace(
      $table_name,
      array(
          'post_id' => $post_id,
          'price' => $entry,
          'updated_at' => current_time('mysql'),
      ),
      array('%d', '%s', '%s')
  );

  return new WP_REST_Response('Data inserted successfully', 200);
}


function update_field_rest($value, $object, $field_name) {
  if (!empty($value)) {
      update_post_meta($object->ID, $field_name, sanitize_text_field($value));
  } else {
      delete_post_meta($object->ID, $field_name);
  }
  return true;
}