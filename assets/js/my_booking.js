document.addEventListener("DOMContentLoaded", function () {
  console.log("My Bookings JS Loaded Successfully!");

  // Cancel Booking Confirmation
  const cancelButtons = document.querySelectorAll(".cancel-btn");

  cancelButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      const confirmCancel = confirm(
        "Are you sure you want to cancel this booking?"
      );
      if (!confirmCancel) {
        e.preventDefault(); // Stop action if user cancels
      }
    });
  });

  // Simple Button Hover Effect (No Scale or Translate)
  const backBtn = document.querySelector(".back-btn");
  if (backBtn) {
    backBtn.addEventListener("mouseenter", function () {
      backBtn.style.backgroundColor = "#00bcd4";
    });

    backBtn.addEventListener("mouseleave", function () {
      backBtn.style.backgroundColor = "#00e5ff";
    });
  }
});
