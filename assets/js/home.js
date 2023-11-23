// Check if the specific ID exists
if (homepage_specificIdElement) {
    document.addEventListener('DOMContentLoaded', function() {
        var elements = homepage_specificIdElement.querySelectorAll('.farsi-numbers');
        var englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        var persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

        elements.forEach(function(el) {
        var text = el.innerText;
        for (var i = 0; i < 10; i++) {
            text = text.replace(new RegExp(englishNumbers[i], 'g'), persianNumbers[i]);
        }
        el.innerText = text;
        });
    });

    const first_table_btn = homepage_specificIdElement.querySelectorAll('.icon-item')[0];
    const first_selected_category_id = first_table_btn.href.slice(first_table_btn.href.lastIndexOf('#')+1);
    homepage_specificIdElement.querySelector('.home_price_table_rows#table'+first_selected_category_id).classList.remove('hidden');
    [...homepage_specificIdElement.querySelectorAll('.icon-item')].forEach(category => {
        category.addEventListener('click', () => {
            const selected_category_id = category.href.slice(category.href.lastIndexOf('#')+1);
            [...homepage_specificIdElement.querySelectorAll('.home_price_table_rows')].forEach(item => item.classList.add('hidden'));
            homepage_specificIdElement.querySelector('.home_price_table_rows#table'+selected_category_id).classList.remove('hidden');
        });
    });
}