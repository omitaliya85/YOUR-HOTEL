document.addEventListener("DOMContentLoaded", () => {
  // Smooth Page Fade-In
  document.body.style.opacity = "0";
  setTimeout(() => {
    document.body.style.opacity = "1";
    document.body.style.transition = "opacity 0.5s ease-in-out";
  }, 100);

  // Input Focus Animations
  const inputs = document.querySelectorAll("input");
  inputs.forEach((input) => {
    input.addEventListener("focus", () => {
      input.style.transform = "scale(1.02)";
    });
    input.addEventListener("blur", () => {
      input.style.transform = "scale(1)";
    });
  });

  // Button Click Effect
  const buttons = document.querySelectorAll(".btn");
  buttons.forEach((btn) => {
    btn.addEventListener("mousedown", () => {
      btn.style.transform = "scale(0.96)";
    });
    btn.addEventListener("mouseup", () => {
      btn.style.transform = "scale(1)";
    });
  });
});
