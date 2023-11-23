// Check if the specific ID exists
var specificIdElement = document.getElementById('price_table_options_main');

if (specificIdElement) {
  // make digits persian
  document.addEventListener('DOMContentLoaded', function() {
    var elements = document.querySelectorAll('#price_table_options_main .farsi-numbers');
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

  function fa_to_en_num(num) {
    var englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    var persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

    for (var i = 0; i < 10; i++) {
      num = num.replace(new RegExp(persianNumbers[i], 'g'), englishNumbers[i]);
    }
    return num;
  }

  function en_to_fa_num(num) {
    var englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    var persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

    for (var i = 0; i < 10; i++) {
      num = num.replace(new RegExp(englishNumbers[i], 'g'), persianNumbers[i]);
    }
    return num;
  }
}