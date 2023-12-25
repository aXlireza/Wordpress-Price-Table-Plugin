<?php

function table_info($title, $id, $link, $list, $category_name) {
    $select_html = '';
    foreach ($list as $item) {
        $name = $item[0];
        $value = $item[1];
        $select_html .= "<option value=\"$value\" >$name</option>";
    }
    echo "<section id=\"$id\" class=\"table_info rtl\">
        <a href=\"$link\">قیمت $title</a>
        <p class=\"rate_checked_warning hidden\">قیمت های درج شده با احتساب %۹ ارزش افـــزوده مـــی بــاشـــد.</p>
        <div class=\"custom-select-wrapper\">
            <select onchange=\"table_info_select('#$id', this.value)\" class=\"custom-select\">
                <option value=\"0\">انتخاب کارخانه</option>
                $select_html
            </select>
        </div>
    </section>";
}

function table_info_by_size($your_select_field_value, $id, $list, $size_value) {
    $select_html = '';
    foreach ($list as $item) {
        $modified = str_replace('.', '_', $item);
        $select_html .= "<option value=\"size$modified\" >$item</option>";
    }
    echo "<section id=\"$id\" class=\"table_info rtl\">
        <span class=\"row-size hidden\">$size_value</span>
        <a class=\"farsi-numbers\">$your_select_field_value:$size_value</a>
        <p class=\"rate_checked_warning\">قیمت های درج شده با احتساب %۹ ارزش افـــزوده مـــی بــاشـــد.</p>
        <div class=\"custom-select-wrapper\">
            <select onchange=\"table_info_select('#pricetable_mainbody_by_size #$id', this.value)\" class=\"custom-select\">
                <option value=\"0\">انتخاب $your_select_field_value</option>
                $select_html
                <!-- More options here -->
            </select>
        </div>
    </section>";
}