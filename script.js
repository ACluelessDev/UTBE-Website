window.addEventListener("scroll", function () {
    const topBar = document.querySelector(".top-bar");

    if (window.scrollY >= 10) {
        topBar.classList.add("topbar-hide");
    } else {
        topBar.classList.remove("topbar-hide");
    }
});