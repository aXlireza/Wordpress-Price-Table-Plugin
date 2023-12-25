<?php

// Mobile cols: more, size, weight, price, change, chart
function table_head($your_select_field_value, $tableid) {
    echo "<nav class=\"navbar\">
        <ul class=\"nav-menu desktop rtl\">
            <li class=\"nav-item item-size\">$your_select_field_value</li>
            <li class=\"nav-item item-info\">مشخصات محصول</li>
            <li class=\"nav-item item-price\">قیمت و نوسان (تومان)</li>
            <li class=\"nav-item item-date\" style=\"text-align: center;\">تاریخ</li>
            <li class=\"nav-item item-btns\">
                <button class=\"nav-button\" tableid=\"$tableid\">دانلود قیمت‌ها</button>
            </li>
        </ul>
        <ul class=\"nav-menu mobile rtl\">
            <li class=\"nav-item\">$your_select_field_value</li>
            <li class=\"nav-item\">وزن (KG)</li>
            <li class=\"nav-item\">قیمت (تومان)</li>
            <li class=\"nav-item\">نوسان</li>
            <li class=\"nav-item\" style=\"text-align: center;\">نمودار</li>
            <li class=\"nav-item\">بیشتر</li>
        </ul>
    </nav>";
}

function table_head_by_size($your_select_field_value) {
    echo "<nav class=\"navbar\">
        <ul class=\"nav-menu rtl\">
            <li class=\"nav-item item-size\">$your_select_field_value</li>
            <li class=\"nav-item item-info\">مشخصات محصول</li>
            <li class=\"nav-item item-price\">قیمت و نوسان (تومان)</li>
            <li class=\"nav-item item-date\" style=\"text-align: center;\">تاریخ</li>
        </ul>
    </nav>";
}