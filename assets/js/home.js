// Check if the specific ID exists
var specificIdElement = document.getElementById('homepage-table-price');

if (specificIdElement) {
    document.addEventListener('DOMContentLoaded', function() {
        var elements = document.querySelectorAll('#homepage-table-price .farsi-numbers');
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

    const first_table_btn = document.querySelectorAll('#homepage-table-price .icon-item')[0];
    const first_selected_category_id = first_table_btn.href.slice(first_table_btn.href.lastIndexOf('#')+1);
    document.querySelector('#homepage-table-price .home_price_table_rows#table'+first_selected_category_id).classList.remove('hidden');
    [...document.querySelectorAll('#homepage-table-price .icon-item')].forEach(category => {
        category.addEventListener('click', () => {
            const selected_category_id = category.href.slice(category.href.lastIndexOf('#')+1);
            [...document.querySelectorAll('#homepage-table-price .home_price_table_rows')].forEach(item => item.classList.add('hidden'));
            document.querySelector('#homepage-table-price .home_price_table_rows#table'+selected_category_id).classList.remove('hidden');
        });
    });
}