window.addEventListener("scroll", function () {
    const topBar = document.querySelector(".top-bar");
    const scrolldownIndicator = document.querySelector(".scroll-down-indicator");

    if (window.scrollY >= 200) {
        topBar.classList.add("topbar-hide");
        scrolldownIndicator.classList.add("fadeout-scroll-indicator");
    } else {
        topBar.classList.remove("topbar-hide");
        scrolldownIndicator.classList.remove("fadeout-scroll-indicator");
    }
});