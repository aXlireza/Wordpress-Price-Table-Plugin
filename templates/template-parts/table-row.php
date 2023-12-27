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
    $price_history_dates = str_replace("','", "_", $price_history_dates);
    
    $price_history_values = json_encode($price_history[0]);
    $price_history_values = substr($price_history_values, 1, strlen($price_history_values)-2);
    if (strlen($price_history_values) == 0) $price_history_values = null;
    $price_history_values = str_replace("\"", "'", $price_history_values);
    $price_history_values = str_replace("','", "_", $price_history_values);

    $sanitized_price = str_replace(',', '', $price);
    // TODO: Add a col for "possible" existance of size/thickness like this page: https://ahanprice.com/Price/%D9%81%D9%84%D9%86%D8%AC-%DA%A9%D9%88%D8%B1

    $title_template = "<a href=\"$link\" class=\"col title-main farsi-numbers text\">$title</a>";
    $size_template = "<div class=\"col row-size\"><span class='text farsi-numbers'>$size</span></div>";
    $weight_template = $weight ? "<div class='col weight'><span class=\"farsi-numbers text\">$weight</span><span class=\"unit\">$weight_unit</span></div>" : '<div></div>';
    $price_template = $change_sign_class_name != 'phone' ? "<span class=\"farsi-numbers pricenumber $change_sign_class_name text\" original_price=\"$sanitized_price\">$price</span>" : '';
    $phonecall_template = $change_sign_class_name == 'phone' ? "<a href=\"tel:$tel\" class=\"phonecall\">
        <span class=\"arrow-icon fa-icon\"></span>
        <span class=\"status-number farsi-numbers text\">تماس بگیرید</span>
    </a>" : '';
    $changes_template = $change_sign_class_name != 'phone' ? "<div class=\"status-box $change_sign_class_name\">
        <span class=\"arrow-icon fa-icon\"></span>
        <span class=\"status-number farsi-numbers text\">$changes</span>
    </div>" : '';
    $chartbtn_template = "<button onClick=\"chartpopup($id, `$price_history_values`, `$price_history_dates`)\" id=\"$id\" class=\"chart-btn fa-icon\">
        <pre>نمودار</pre>
    </button>";

    $mobile_price_template = $change_sign_class_name != 'phone' ? "<div class='price-information col'><div class='price'>$price_template</div></div>" : "<div style='display: none;'></div>";
    $mobile_changes_template = $change_sign_class_name != 'phone' ? "<div class='price-information col'>$changes_template</div>" : '';

    $mobile_phonecall_template = $change_sign_class_name == 'phone' ? "<div style='grid-column: 3/5;'>$phonecall_template</div>" : '';

    echo "<div class=\"info_row_container $class\" id=\"$customid\">
        <div id=\"$id\" class=\"info-row rtl\">

            <article class='info-row-desktop subrow'>
                $size_template
                <div class=\"stock-title\">
                    $title_template
                    <span class=\"title-sub\">$desc $weight_template</span>
                </div>
                <div class=\"price-information\">
                    $changes_template
                    <div class=\"price\">
                        $price_template
                        <span class=\"currency\">تومان</span>
                    </div>
                    $phonecall_template
                </div>
                <div class=\"date-tag\">
                    <div>
                        <span class=\"date farsi-numbers\">$date</span>
                        <span class=\"tag\">امروز</span>
                    </div>
                </div>
                <div class=\"action-buttons\">
                    $chartbtn_template
                </div>
                <a href=\"$link\" class=\"hideonmobile description-label\">توضیحات</a>
            </article>

            <article class='info-row-mobile subrow'>
                $size_template
                $weight_template
                $mobile_price_template
                $mobile_phonecall_template
                $mobile_changes_template
                <div class=\"action-buttons col\">$chartbtn_template</div>
                
                <button class='moredetails accordion-button fa-icon'></button>
                <div class='moredetails accordion-content'>
                    <div>
                        <ul style='margin: 0;'>
                            <li>$desc</li>
                            <li>$title</li>
                        </ul>
                        <a href=\"$link\" class=\"description-label fa-info-circle fa-icon\">توضیحات بیشتر</a>
                    </div>
                </div>
            </article>

        </div>
    </div>

    
    <div class=\"popup-wrapper\" id=\"popupWrapper_$id\">
        <div class=\"popup-content\">
            <button onClick=\"close_chart_popup($id)\" id=\"close_chart_popup_$id\" class=\"exit-button\">X</button>
            <canvas id=\"pricetable_pricehistory_$id\"></canvas>
        </div>
    </div>";

}
