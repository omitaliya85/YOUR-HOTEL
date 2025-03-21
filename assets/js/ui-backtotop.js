// =================== Back to Top Button ===================
const backToTopButton = document.getElementById("backToTop");

window.addEventListener("scroll", () => {
  if (window.scrollY > 300) {
    backToTopButton.classList.add("show-btn");
  } else {
    backToTopButton.classList.remove("show-btn");
  }
});

// Scroll to top smoothly
backToTopButton.addEventListener("click", () => {
  window.scrollTo({
    top: 0,
    behavior: "smooth",
  });
});

// =================== Auto-Hide Button on Idle ===================
let isScrolling;
window.addEventListener("scroll", () => {
  backToTopButton.classList.add("show-btn");
  window.clearTimeout(isScrolling);

  // Auto-hide after 3 seconds
  isScrolling = setTimeout(() => {
    backToTopButton.classList.remove("show-btn");
  }, 3000);
});

// =================== Button Hover Effects ===================
backToTopButton.addEventListener("mouseenter", () => {
  backToTopButton.classList.add("hover-glow");
});

backToTopButton.addEventListener("mouseleave", () => {
  backToTopButton.classList.remove("hover-glow");
});
