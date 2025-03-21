document.addEventListener("DOMContentLoaded", () => {
  // Smooth Fade-In Animation for Footer
  const footer = document.querySelector(".hotel-footer");
  if (footer) {
    footer.style.opacity = "0";
    setTimeout(() => {
      footer.style.opacity = "1";
      footer.style.transition = "opacity 0.8s ease-in-out";
    }, 300);
  }

  // Social Icons Hover Animation
  const socialLinks = document.querySelectorAll(".hotel-social a");
  socialLinks.forEach((link) => {
    link.addEventListener("mouseenter", () => {
      link.style.transform = "scale(1.1)";
      link.style.transition = "transform 0.3s ease";
    });

    link.addEventListener("mouseleave", () => {
      link.style.transform = "scale(1)";
    });
  });

  // Contact Info Hover Effect
  const contactItems = document.querySelectorAll(".hotel-contact p");
  contactItems.forEach((item) => {
    item.addEventListener("mouseenter", () => {
      item.style.color = "#ffcc00";
      item.style.transition = "color 0.3s ease";
    });

    item.addEventListener("mouseleave", () => {
      item.style.color = "";
    });
  });
});
