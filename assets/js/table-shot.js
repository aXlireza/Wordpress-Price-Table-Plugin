// Check if the specific ID exists
if (specificIdElement) {
    document.addEventListener('DOMContentLoaded', function() {
        [...specificIdElement.querySelectorAll('.nav-button')].forEach(btn => {
            const tableid = btn.getAttribute('tableid');
            btn.addEventListener('click', function() {
                html2canvas(specificIdElement.getElementById(tableid)).then(function(canvas) {
                    // Create an image from the canvas
                    var img = canvas.toDataURL('image/png');

                    // Create a link to download the image
                    var downloadLink = specificIdElement.createElement('a');
                    downloadLink.href = img;
                    downloadLink.download = 'captured-image.png';

                    // Trigger the download
                    downloadLink.click();
                });
            });
        });
    })
}