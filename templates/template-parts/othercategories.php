<?php

function small_cat_box($item) {
    $title = $item['name'];
    $link = $item['link'];
    $img_src = $item['thumbnail_src'];
    $alt = $item['name'];
    
    echo "<a href=\"$link\" class=\"icon-item\">
        <img src=\"$img_src\" alt=\"$alt\">
        <span>$title</span>
    </a>";
}

function price_cat_list($data) {
    echo "<div class=\"categoryitems rtl\">";
    foreach ($data as $item) small_cat_box($item);
    echo "</div>";
}