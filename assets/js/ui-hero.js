// Smooth Scroll for Hero Section Button
document.addEventListener("DOMContentLoaded", function () {
  const scrollBtn = document.querySelector(".hotel-hero-scroll-btn");

  if (scrollBtn) {
    scrollBtn.addEventListener("click", function (e) {
      e.preventDefault();
      const targetSection = document.querySelector("#features");

      if (targetSection) {
        targetSection.scrollIntoView({
          behavior: "smooth",
          block: "start",
        });
      }
    });
  }
});
