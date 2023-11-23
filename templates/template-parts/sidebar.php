<?php

function table_sidebar($factories, $sizes, $size_name) {
    $factories_str = [];
    foreach ($factories as $key => $value) array_push($factories_str, $value);
    $factories_str = json_encode($factories_str);

    $sizes_str = [];
    foreach ($sizes as $key => $value) {
        $myObject = new stdClass();
        $myObject->title = $value;
        $myObject->id = floatval($value);
        array_push($sizes_str, $myObject);
    }
    $sizes_str = json_encode($sizes_str);

    echo "<sidebar id=\"tablefiltersidebar\">
        <div class=\"sidebar-header\">
            <h2>اولویت نمایش بر اساس</h2>
        </div>
        <div class=\"button-group\">
            <button id=\"btn-factories\" class=\"active\"><i class=\"fa-icon\"></i> کارخانه</button>
            <button id=\"btn-sizes\"><i class=\"fa-icon\"></i> $size_name</button>
        </div>
        <div class=\"tag-input-container\" data=\"factory_tags\">
            <div class=\"selected-tags\"></div>
            <input type=\"text\" class=\"tag-input\" placeholder=\"کارخانه مورد نظر\" />
            <div class=\"tag-list hidden\"></div>
        </div>
        <div class=\"tag-input-container\" data=\"size_tags\">
            <div class=\"selected-tags\"></div>
            <input type=\"text\" class=\"tag-input\" placeholder=\"$size_name مورد نظر\" />
            <div class=\"tag-list hidden\"></div>
        </div>
    </sidebar>
    <script>
        const filter_data_tags = {
            'factory_tags': $factories_str,
            'size_tags': $sizes_str,
        }
    </script>";
}