// ====================== Page Load Animation ======================
document.addEventListener("DOMContentLoaded", () => {
  const contactCard = document.querySelector(".nitro-contact-card");
  contactCard.classList.add("fade-in");
});

// ====================== Input Focus Glow ======================
const inputs = document.querySelectorAll(
  "#nitro-contact-form input, #nitro-contact-form textarea"
);

inputs.forEach((input) => {
  input.addEventListener("focus", () => {
    input.classList.add("input-glow");
  });

  input.addEventListener("blur", () => {
    input.classList.remove("input-glow");
  });
});

// ====================== Back to Top Button ======================
const backToTopBtn = document.createElement("button");
backToTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
backToTopBtn.classList.add("nitro-back-to-top");
document.body.appendChild(backToTopBtn);

window.addEventListener("scroll", () => {
  if (window.scrollY > 300) {
    backToTopBtn.classList.add("show-btn");
  } else {
    backToTopBtn.classList.remove("show-btn");
  }
});

backToTopBtn.addEventListener("click", () => {
  window.scrollTo({
    top: 0,
    behavior: "smooth",
  });
});

// ====================== Form Validation ======================
const contactForm = document.getElementById("nitro-contact-form");

contactForm.addEventListener("submit", (e) => {
  e.preventDefault();
  const name = document.getElementById("name").value.trim();
  const email = document.getElementById("email").value.trim();
  const message = document.getElementById("message").value.trim();

  if (name === "" || email === "" || message === "") {
    alert("Please fill out all fields.");
    return false;
  }

  if (!validateEmail(email)) {
    alert("Please enter a valid email address.");
    return false;
  }

  alert("Message Sent Successfully! We'll get back to you soon.");
  contactForm.reset();
});

// ====================== Email Validation ======================
function validateEmail(email) {
  const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-z]{2,}$/;
  return re.test(email);
}

// ====================== Glowing Button Animation ======================
const submitBtn = document.querySelector(".nitro-btn-glow");

submitBtn.addEventListener("mouseenter", () => {
  submitBtn.classList.add("btn-glow");
});

submitBtn.addEventListener("mouseleave", () => {
  submitBtn.classList.remove("btn-glow");
});
