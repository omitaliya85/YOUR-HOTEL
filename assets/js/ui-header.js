document.addEventListener("DOMContentLoaded", () => {
  // Smooth Page Fade-In
  document.body.style.opacity = "0";
  setTimeout(() => {
    document.body.style.opacity = "1";
    document.body.style.transition = "opacity 0.6s ease-in-out";
  }, 100);

  // Button Click Effects
  document.querySelectorAll(".hotel-btn").forEach((btn) => {
    btn.addEventListener(
      "mousedown",
      () => (btn.style.transform = "scale(0.95)")
    );
    btn.addEventListener("mouseup", () => (btn.style.transform = "scale(1)"));
  });

  // Navbar Links Hover Effect
  document.querySelectorAll(".hotel-nav-item").forEach((navItem) => {
    navItem.addEventListener("mouseenter", () => {
      navItem.style.color = "#00bcd4";
      navItem.style.transition = "color 0.3s ease-in-out";
    });
    navItem.addEventListener("mouseleave", () => {
      navItem.style.color = "#ddd";
    });
  });
});
