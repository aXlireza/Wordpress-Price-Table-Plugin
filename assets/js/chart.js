// Check if the specific ID exists
var specificIdElement = document.getElementById('price_table_options_main');

if (specificIdElement) {
let popupchart_on_pricehistory = null

function chartpopup(post_id, prices, dates) {

  // check for all the popups to be closed
  [...document.querySelectorAll(`#price_table_options_main .popup-wrapper`)].forEach(popup_wrapper => popup_wrapper.classList.remove("active"))
  if (popupchart_on_pricehistory != null) popupchart_on_pricehistory.destroy()

  // reformat the passed parameters to readable data for js
  prices = prices.replaceAll("'", "").split("_").map(item => Number(item))
  dates = dates.replaceAll("'", "").split("_")

  document.querySelector(`#price_table_options_main #pricetable_pricehistory_${post_id}`).innerHTML = ''
  popupchart_on_pricehistory = new Chart(document.querySelector(`#price_table_options_main #pricetable_pricehistory_${post_id}`), {
    type: 'line',
    data: {
    labels: dates, //['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
    datasets: [{
        label: 'price',
        data: prices, //[12, 19, 3, 5, 2, 3],
        borderWidth: 1
    }]
    },
    options: {
      scales: {y: {beginAtZero: true}}
    }
  });

  document.querySelector(`#price_table_options_main #popupWrapper_${post_id}`).classList.toggle("active")
}

function close_chart_popup(post_id) {
  document.querySelector(`#price_table_options_main #popupWrapper_${post_id}`).classList.toggle("active")
  popupchart_on_pricehistory.destroy()
}
}