<?php
// post-content.php

// Define a function that accepts parameters

/**
 * - $id: the post id
 * - $link: the link of the post
 * - $title: the title value of the post
 * - $desc: the description
 * - $size: the size value of the post
 * - $weight: the weight value of the post
 * - $weight_unit: the unit value of the weight of the post
 * - $date: the date of the post
 * - $price: the price value of the post
 * - $change_sign: whether the change rate is positive or negative or 0
 * - $changes: the change value
 * - $tel: the tel number that in case the CTA is active the number will be setup as call link
 * - $class: extra classes
 * - $customid: custom id
 */
function table_row($id, $link, $title, $desc, $size, $weight, $weight_unit, $date, $price, $change_sign, $changes, $price_history, $tel, $class, $customid) {
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

    $sanitized_price = str_replace(',', '', $price);

    echo "<div class=\"info_row_container $class\" id=\"$customid\">
        <div id=\"$id\" class=\"info-row rtl\">
            <div class=\"row-size farsi-numbers\">$size</div>
            <div class=\"stock-title\">
                <a href=\"$link\" class=\"title-main farsi-numbers\">$title</a>
                <span class=\"title-sub\">$desc<span class=\"weight farsi-numbers\">$weight</span><span class=\"unit\">$weight_unit</span></span>
            </div>
            <div class=\"price-information $change_sign_class_name\">
                <div class=\"status-box\">
                    <span class=\"arrow-icon fa-icon\"></span>
                    <span class=\"status-number farsi-numbers\">$changes</span>
                </div>
                <div class=\"price\">
                    <span class=\"farsi-numbers pricenumber\" original_price=\"$sanitized_price\">$price</span>
                    <span class=\"currency\">تومان</span>
                </div>
                <a href=\"tel:$tel\" class=\"phonecall\">
                    <span class=\"arrow-icon fa-icon\"></span>
                    <span class=\"status-number farsi-numbers\">تماس بگیرید</span>
                </a>
            </div>
            <div class=\"date-tag\">
                <div>
                    <span class=\"date farsi-numbers\">$date</span>
                    <span class=\"tag\">امروز</span>
                </div>
            </div>
            <div class=\"action-buttons\">
                <button onClick=\"chartpopup($id, `$price_history_values`, `$price_history_dates`)\" id=\"$id\" class=\"chart-btn fa-icon\">
                    <pre>نمودار</pre>
                </button>
            </div>
            <a href=\"$link\" class=\"description-label\">توضیحات</a>
        </div>
    </div>

    
    <div class=\"popup-wrapper\" id=\"popupWrapper_$id\">
        <div class=\"popup-content\">
            <button onClick=\"close_chart_popup($id)\" id=\"close_chart_popup_$id\" class=\"exit-button\">X</button>
            <canvas id=\"pricetable_pricehistory_$id\"></canvas>
        </div>
    </div>";

}
