<?php

function table_rate() {
    echo "<div class=\"ratebox-container rtl\">
        <div class=\"checkbox-container\" onClick=\"checkbox_handler()\">
            <input type=\"checkbox\" />
            <label for=\"toggleText\">نمایش قیمت با ارزش افزوده</label>
        </div>
        <div class=\"text-container\">
            قیمت های درج شده برای یک ظرفیت می بـاشد.
        </div>
    </div>";
}

function table_rate_by_size() {
    echo "<div class=\"ratebox-container rtl\">
        <div class=\"text-container\">
        قیمت های درج شده برای یک ظرفیت و با احتساب %۹ ارزش افزوده می بـاشد.
        </div>
    </div>";
}