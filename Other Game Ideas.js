window.addEventListener("scroll", function () {
    const topBar = document.querySelector(".top-bar");

    if (window.scrollY >= 200) {
        topBar.classList.add("topbar-hide");
    } else {
        topBar.classList.remove("topbar-hide");
    }
});

const ideasCards = document.querySelectorAll(".ideas-card");

const ideasObserver = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
        if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            ideasObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.15 });

ideasCards.forEach(function (card) {
    card.classList.add("ideas-animate");
    ideasObserver.observe(card);
});