document.addEventListener("DOMContentLoaded", function () {
  const features = document.querySelectorAll(".hotel-feature-item");

  features.forEach((feature, index) => {
    feature.style.opacity = "0";
    feature.style.transform = "translateY(20px)";
    feature.style.transition = `opacity 0.6s ease ${
      index * 0.1
    }s, transform 0.6s ease ${index * 0.1}s`;
  });

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = "1";
          entry.target.style.transform = "translateY(0)";
        }
      });
    },
    { threshold: 0.2 }
  );

  features.forEach((feature) => observer.observe(feature));
});
