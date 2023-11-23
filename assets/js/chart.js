// Check if the specific ID exists
// var specificIdElement = document.getElementById('price_table_options_main');

if (specificIdElement) {
  let popupchart_on_pricehistory = null
  // specificIdElement = document.getElementById('price_table_options_main');

  function chartpopup(post_id, prices, dates) {

    // check for all the popups to be closed
    [...specificIdElement.querySelectorAll(`.popup-wrapper`)].forEach(popup_wrapper => popup_wrapper.classList.remove("active"))
    if (popupchart_on_pricehistory != null) popupchart_on_pricehistory.destroy()

    // reformat the passed parameters to readable data for js
    prices = prices.replaceAll("'", "").split("_").map(item => Number(item))
    dates = dates.replaceAll("'", "").split("_")

    specificIdElement.querySelector(`#pricetable_pricehistory_${post_id}`).innerHTML = ''
    popupchart_on_pricehistory = new Chart(specificIdElement.querySelector(`#pricetable_pricehistory_${post_id}`), {
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

    specificIdElement.querySelector(`#popupWrapper_${post_id}`).classList.toggle("active")
  }

  function close_chart_popup(post_id) {
    specificIdElement.querySelector(`#popupWrapper_${post_id}`).classList.toggle("active")
    popupchart_on_pricehistory.destroy()
  }
}