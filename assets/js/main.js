// =================== Smooth Scroll for Anchor Links ===================
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute("href"));
    if (target) {
      target.scrollIntoView({
        behavior: "smooth",
        block: "start",
      });
    }
  });
});

// =================== Room Image Lazy Load ===================
document.addEventListener("DOMContentLoaded", () => {
  const roomImages = document.querySelectorAll(".room-img");
  roomImages.forEach((img) => {
    img.addEventListener("error", () => {
      img.src = "../uploads/rooms/default-room.jpeg"; // Fallback image
    });
  });
});

// =================== Advanced Button Hover Animation ===================
const ctaButtons = document.querySelectorAll(".cta-btn");
ctaButtons.forEach((btn) => {
  btn.addEventListener("mouseenter", () => {
    btn.classList.add("cta-hover");
  });
  btn.addEventListener("mouseleave", () => {
    btn.classList.remove("cta-hover");
  });
});

// =================== Scroll Event for Fixed Navbar ===================
window.addEventListener("scroll", () => {
  const header = document.querySelector(".hotel-header");
  if (window.scrollY > 100) {
    header.classList.add("fixed-nav");
  } else {
    header.classList.remove("fixed-nav");
  }
});

// =================== Hero Section Fade-in Effect ===================
window.addEventListener("load", () => {
  const heroSection = document.querySelector(".hero-section");
  heroSection.classList.add("fade-in");
});
