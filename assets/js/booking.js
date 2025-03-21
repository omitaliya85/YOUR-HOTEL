// ===================== Booking Page Nitro JS =====================
document.addEventListener("DOMContentLoaded", () => {
  console.log("Booking Page Loaded Successfully!");

  // ===================== Smooth Scroll for Booking =====================
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      document.querySelector(this.getAttribute("href")).scrollIntoView({
        behavior: "smooth",
        block: "start",
      });
    });
  });

  // ===================== Booking Form Validation =====================
  const bookingForm = document.querySelector(".booking-form");
  const checkInInput = document.querySelector("#booking-checkin");
  const checkOutInput = document.querySelector("#booking-checkout");
  const guestsInput = document.querySelector("#booking-guests");

  bookingForm.addEventListener("submit", (e) => {
    let isValid = true;

    if (!validateDates(checkInInput.value, checkOutInput.value)) {
      alert("Invalid Check-in/Check-out dates. Please check again!");
      isValid = false;
    }

    if (guestsInput.value <= 0 || isNaN(guestsInput.value)) {
      alert("Please enter a valid number of guests.");
      isValid = false;
    }

    if (!isValid) {
      e.preventDefault(); // Prevent form submission if validation fails
    }
  });

  // ===================== Date Validation =====================
  function validateDates(checkIn, checkOut) {
    const checkInDate = new Date(checkIn);
    const checkOutDate = new Date(checkOut);
    return checkOutDate > checkInDate && checkInDate >= new Date();
  }

  // ===================== Scroll to Top Button =====================
  const scrollToTopBtn = document.querySelector(".scroll-to-top-btn");

  window.addEventListener("scroll", () => {
    if (window.scrollY > 300) {
      scrollToTopBtn.classList.add("show-btn");
    } else {
      scrollToTopBtn.classList.remove("show-btn");
    }
  });

  scrollToTopBtn.addEventListener("click", () => {
    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });
  });

  console.log("🚀 Booking Page JS Loaded Successfully!");
});
