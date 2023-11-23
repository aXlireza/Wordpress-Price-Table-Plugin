<?php

// Hook into WordPress admin menu to display the upload form
add_action('admin_menu', 'custom_price_table_upload_page');
// Add Excel/CSV upload page for operators as a subpage under Price Tables menu
function custom_price_table_upload_page() {
    add_submenu_page(
        'edit.php?post_type=price_table', // Parent menu (Price Tables)
        'Upload Price Table',
        'Upload Price Table',
        'manage_options',
        'price_table_upload',
        'custom_price_table_upload_page_callback'
    );
}

function custom_price_table_upload_page_callback() {
    // Handle the upload page content here
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['price_table_upload_file'])) {
        // Handle file upload and data insertion/update logic here
        // Use $_FILES['price_table_upload_file'] to access the uploaded file
    }

    echo '<div class="wrap">';
    echo '<h2>Upload Price Table</h2>';
    echo '<form method="post" enctype="multipart/form-data">';
    echo '<label for="price_table_upload_file">Choose file:</label>';
    echo '<input type="file" id="price_table_upload_file" name="price_table_upload_file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">';
    echo '<input type="submit" name="submit" id="submit" class="button button-primary" value="Upload">';
    echo '</form>';
    echo '</div>';
}


// Hook into the file upload form submission
add_action('admin_init', 'handle_price_table_upload');
// Handle the file upload and import data
function handle_price_table_upload() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['price_table_upload_file'])) {
        $file = $_FILES['price_table_upload_file'];

        // Check for errors in the file upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            echo '<div class="error"><p>File upload error.</p></div>';
            return;
        }

        // Check if the file is a CSV or Excel file
        $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (!in_array($file_ext, ['csv', 'xls', 'xlsx'])) {
            echo '<div class="error"><p>Invalid file format. Please upload a CSV, XLS, or XLSX file.</p></div>';
            return;
        }

        // Process the uploaded file
        $uploaded_file = $file['tmp_name'];
        if ($file_ext === 'csv') {
            handle_csv_import($uploaded_file);
        } else {
            handle_excel_import($uploaded_file);
        }
    }
}

include plugin_dir_path(__FILE__) . '/simplexlsx/SimpleXLSX.php';
use Shuchkin\SimpleXLSX;

// Handle XLSX file import without PhpSpreadsheet
function handle_excel_import($file_path) {
    
    if ($xlsx = SimpleXLSX::parse($file_path)) {
        $rows = $xlsx->rows();
        $header = $rows[0];
        for ($i=1; $i < count($rows); $i++) { 
            $row = $rows[$i];
            $data = array_combine($header, $row);
            insert_or_update_price_table_wp_functions($data);
        }
    } else {
        echo '<div class="error"><p>Failed to parse the XLSX file.</p></div>';
    }
}
// Handle CSV file import using WordPress functions
function handle_csv_import($file_path) {
    $csv_file = fopen($file_path, 'r');
    $header = fgetcsv($csv_file); // Assuming the first row is the header

    while ($row = fgetcsv($csv_file)) {
        $data = array_combine($header, $row);
        insert_or_update_price_table_wp_functions($data);
    }

    fclose($csv_file);
}

// Insert or update price table using WordPress functions
function insert_or_update_price_table_wp_functions($data) {
    // Check if the post already exists based on title
    $existing_post = get_page_by_path(sanitize_title($data['عنوان']), OBJECT, 'price_table');

    if ($existing_post) {
        // Update existing post
        $post_id = $existing_post->ID;
    } else {
        // Create a new post
        $post_id = wp_insert_post(array(
            'post_title'   => $data['عنوان'],
            'post_type'    => 'price_table',
            'post_status'  => 'publish',
        ));
    }

    // Set custom fields
    update_post_meta($post_id, '_thickness', $data['ضخامت(mm)']);
    update_post_meta($post_id, '_weight', $data['وزن']);
    update_post_meta($post_id, '_size', $data['ابعاد(cm)']);
    update_post_meta($post_id, '_alloy', $data['آلیاژ']);
    update_post_meta($post_id, '_unit', $data['واحد']);
    if (isset($data['قیمت']) && $data['قیمت'] != '_') {
        update_post_meta($post_id, '_price', $data['قیمت']);
        update_post_meta($post_id, '_cta', '');
    }
    else update_post_meta($post_id, '_cta', 'on');
}