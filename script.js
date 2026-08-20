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

/* Thanks, Google (or Brave technically) */
const whatSection = document.querySelector(".utbe-what");
const whatText = document.querySelector(".utbe-what-text");
const whatPortrait = document.querySelector(".utbe-what-portrait");
const whatDescription = document.querySelector(".utbe-what-description");

const whoSection = document.querySelector(".utbe-who");
const whoText = document.querySelector(".utbe-who-text");
const whoPortrait = document.querySelector(".utbe-who-portrait");
const whoDescription = document.querySelector(".utbe-who-description");

const whySection = document.querySelector(".utbe-why");
const whyText = document.querySelector(".utbe-why-text");
const whyPortrait = document.querySelector(".utbe-why-portrait");
const whyDescription = document.querySelector(".utbe-why-description");


if (whatDescription) {
    const paragraphs = whatDescription.querySelectorAll("p");

    paragraphs.forEach((paragraph) => {
        const words = paragraph.textContent.trim().split(/\s+/);

        /* Looks a bit complex but thank you Reddit for helping me find out how to exclude a specific span style from the JavaScript code */
        /* This will just basically look for the words "unwillingness", "to", and "die." Then it will set all those text to red if it finds it in the WHAT description */
        /* It's needed because the JavaScript basically destroys the HTML structure in the previous line just above this one */
        paragraph.innerHTML = words
            .map((word, index) => {
                const isRed = ["unwillingness", "to", "die."].includes(word);
                return `<span style="transition-delay: ${index * 0.045}s; ${isRed ? "color: #780606;" : ""}"> ${word} </span>`;
            })

            .join(" ");
    });
}

if (whoDescription) {
    const paragraphs = whoDescription.querySelectorAll("p");

    paragraphs.forEach((paragraph) => {
        const words = paragraph.textContent.trim().split(/\s+/);

        paragraph.innerHTML = words
            .map((word, index) => {
                return `<span style="transition-delay: ${index * 0.045}s">${word}</span>`;
            })
            .join(" ");
    });
}

if (whyDescription) {
    const paragraphs = whyDescription.querySelectorAll("p");

    paragraphs.forEach((paragraph) => {
        const words = paragraph.textContent.trim().split(/\s+/);

        paragraph.innerHTML = words
            .map((word, index) => {
                const isRed = ["suffering", "from", "cancer."].includes(word);
                return `<span style="transition-delay: ${index * 0.045}s; ${isRed ? "color: #780606;" : ""}"> ${word} </span>`;
            })
            .join(" ");
    });
}

/* Had to look up a tutorial on how to make an animation play SPECIFICALLY when it's being viewed
    This can be achieved via IntersectionObserver.
    Credits go to YouTuber "Coding2GO" found on this video: https://youtu.be/Jo8ABAJtMM0
    I just changed it a bit to fit with multiple parts of my "WHAT" portion
 */
if (whatSection && whatText && whatPortrait) {
    const observer = new IntersectionObserver(
        (entries, observer) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    whatText.classList.add("is-visible");
                    whatPortrait.classList.add("is-visible");

                    if (whatDescription) {
                        whatDescription.classList.add("is-visible");
                    }

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

if (whoSection && whoText && whoPortrait) {
    const observer = new IntersectionObserver(
        (entries, observer) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    whoText.classList.add("is-visible");
                    whoPortrait.classList.add("is-visible");

                    if (whoDescription) {
                        whoDescription.classList.add("is-visible");
                    }

                    observer.unobserve(entry.target);
                }
            });
        },
        {
            threshold: 0.25
        }
    );

    observer.observe(whoSection);
}

if (whySection && whyText && whyPortrait) {
    const observer = new IntersectionObserver(
        (entries, observer) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    whyText.classList.add("is-visible");
                    whyPortrait.classList.add("is-visible");

                    if (whyDescription) {
                        whyDescription.classList.add("is-visible");
                    }

                    observer.unobserve(entry.target);
                }
            });
        },
        {
            threshold: 0.25
        }
    );

    observer.observe(whySection);
}