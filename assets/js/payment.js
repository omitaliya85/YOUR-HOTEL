// Payment.js - Basic JS for Confirmation
document.addEventListener("DOMContentLoaded", () => {
  const confirmBtn = document.querySelector(".btn-confirm");

  confirmBtn.addEventListener("click", (e) => {
    const paymentType = document.querySelector("#payment_type").value;

    if (!paymentType) {
      alert("Please select a payment method.");
      e.preventDefault();
    } else {
      alert(`Proceeding with ${paymentType} payment...`);
    }
  });
});
