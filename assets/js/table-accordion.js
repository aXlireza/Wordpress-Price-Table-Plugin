// Check if the specific ID exists
if (specificIdElement) {
  var acc = specificIdElement.querySelectorAll(".info_row_container .moredetails.accordion-button");
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