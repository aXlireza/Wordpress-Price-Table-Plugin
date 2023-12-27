// Check if the specific ID exists
if (specificIdElement) setup_accordion_functionality(specificIdElement?.querySelectorAll(".info_row_container .moredetails.accordion-button") || [])
if (homepage_specificIdElement) setup_accordion_functionality(homepage_specificIdElement?.querySelectorAll(".info_row_container .moredetails.accordion-button") || [])

function setup_accordion_functionality(acc) {
    var i;
    for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var content = this.nextElementSibling;
            if (content.style.display === "block") {
                content.style.display = "none";
            } else {
                content.style.display = "block";
            }
        });
    }
}