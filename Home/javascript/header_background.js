// Initially white background header, onscroll add grey background color
// when the browser screen size is small, set the background to grey
// Add this function to "window.onscroll"
function headerInitialWhite() {
    var navSection = document.getElementById("navbar-section");
    // Get the offset position of the navbar
    var stickyPos = navSection.offsetTop;
    if (window.pageYOffset > stickyPos) {
        navSection.classList.add("navbar-custom");
    } else {
        // If the triple bar is not clicked, then the background color should be removed
        if (!document.getElementById("navbarSupportedContent").classList.contains("show")) {
            navSection.classList.remove("navbar-custom");
        }
    }
}

// Initially, add grey background color to header
// Add this function to "window.onload"
function headerInitialGray() {
    const setNavColor = setTimeout(function () {
        document.getElementById("navbar-section").classList.add("navbar-custom");
        clearTimeout(setNavColor);
    }, 50);
}