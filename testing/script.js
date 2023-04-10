document.addEventListener("DOMContentLoaded", function() {
    var images = document.querySelectorAll(".banner img");
    var currentIndex = 0;
    var interval = setInterval(changeImage, 3000); // Change image every 3 seconds

    function changeImage() {
        images[currentIndex].classList.remove("active");
        currentIndex = (currentIndex + 1) % images.length;
        images[currentIndex].classList.add("active");
    }
});