document.addEventListener("DOMContentLoaded", function () {
  const testimonials = document.querySelectorAll(".hotel-testimonial-item");
  const dots = document.querySelectorAll(".dot");
  let currentIndex = 0;

  function showTestimonial(index) {
    testimonials.forEach((testimonial, i) => {
      testimonial.style.display = i === index ? "block" : "none";
      dots[i].classList.toggle("active", i === index);
    });
  }

  // Show first testimonial by default
  showTestimonial(currentIndex);

  // Auto-Switch Testimonials every 5 seconds
  setInterval(() => {
    currentIndex = (currentIndex + 1) % testimonials.length;
    showTestimonial(currentIndex);
  }, 5000);

  // Handle Dot Click
  dots.forEach((dot, index) => {
    dot.addEventListener("click", () => {
      showTestimonial(index);
      currentIndex = index;
    });
  });
});
