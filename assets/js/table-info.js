// Check if the specific ID exists
var specificIdElement = document.getElementById('price_table_options_main');

if (specificIdElement) {
    function table_info_select(id, hash) {
        specificIdElement.querySelector(`${id} select`).selectedIndex = 0
        window.location.hash = hash;
    }
}