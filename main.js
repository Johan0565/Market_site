document.addEventListener("DOMContentLoaded", () => {
    const slider = document.querySelector(".hero-slider");
    if (!slider) return;

    const slides = Array.from(slider.querySelectorAll(".slide"));
    const dotsContainer = slider.querySelector(".slider-dots");

    slides.forEach((_, index) => {
        const dot = document.createElement("button");
        dot.classList.add("slider-dot");
        if (index === 0) dot.classList.add("active");
        dot.setAttribute("data-index", index.toString());
        dotsContainer.appendChild(dot);
    });

    const dots = Array.from(dotsContainer.querySelectorAll(".slider-dot"));
    let current = 0;

    function showSlide(index) {
        slides[current].classList.remove("active");
        dots[current].classList.remove("active");
        current = index;
        slides[current].classList.add("active");
        dots[current].classList.add("active");
    }

    dots.forEach(dot => {
        dot.addEventListener("click", () => {
            const index = parseInt(dot.getAttribute("data-index"), 10);
            showSlide(index);
        });
    });

    setInterval(() => {
        const next = (current + 1) % slides.length;
        showSlide(next);
    }, 5000);
})
