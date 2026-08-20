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

/* Had to look up a tutorial on how to make an animation play SPECIFICALLY when it's being viewed
    This can be achieved via IntersectionObserver.
    Credits go to YouTuber "Coding2GO" found on this video: https://youtu.be/Jo8ABAJtMM0
    I just changed it a bit to fit with multiple parts of my "WHAT" portion
 */
const whatSection = document.querySelector(".utbe-what");
const whatText = document.querySelector(".utbe-what-text");
const whatPortrait = document.querySelector(".utbe-what-portrait");

if (whatSection && whatText && whatPortrait) {
    const observer = new IntersectionObserver(
        (entries, observer) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    whatText.classList.add("is-visible");
                    whatPortrait.classList.add("is-visible");

                    observer.unobserve(entry.target);
                }
            });
        },
        {
            threshold: 0.25
        }
    );

    observer.observe(whatSection);
}
