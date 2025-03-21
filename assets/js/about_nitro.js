// ====================== Page Loaded Animation ======================
document.addEventListener("DOMContentLoaded", () => {
  const heroTitle = document.querySelector(".nitro-title");
  const heroTagline = document.querySelector(".nitro-tagline");
  const aboutSection = document.querySelector(".nitro-about-section");

  // Fade-in Effects
  heroTitle.classList.add("fade-in-up");
  setTimeout(() => {
    heroTagline.classList.add("fade-in-up");
  }, 500);

  // Scroll Triggered Animations
  window.addEventListener("scroll", () => {
    const scrollPosition = window.scrollY;

    if (scrollPosition > aboutSection.offsetTop - window.innerHeight / 1.2) {
      aboutSection.classList.add("fade-in-up");
    }
  });
});

// ====================== Glow Effect on Hover ======================
const nitroCards = document.querySelectorAll(".nitro-card");

nitroCards.forEach((card) => {
  card.addEventListener("mouseenter", () => {
    card.classList.add("nitro-glow");
  });

  card.addEventListener("mouseleave", () => {
    card.classList.remove("nitro-glow");
  });
});

// ====================== Scroll to Section Smooth ======================
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", (e) => {
    e.preventDefault();
    document.querySelector(this.getAttribute("href")).scrollIntoView({
      behavior: "smooth",
      block: "start",
    });
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

// ====================== Dynamic Glow for Sections ======================
const sections = document.querySelectorAll(
  ".nitro-about-section, .nitro-amenities-section"
);

window.addEventListener("scroll", () => {
  sections.forEach((section) => {
    const sectionTop = section.getBoundingClientRect().top;
    if (sectionTop >= 0 && sectionTop <= window.innerHeight * 0.75) {
      section.classList.add("section-glow");
    } else {
      section.classList.remove("section-glow");
    }
  });
});

// ====================== Lazy Load Images ======================
const images = document.querySelectorAll("img[data-src]");
const lazyLoad = (target) => {
  const io = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const img = entry.target;
        img.src = img.dataset.src;
        observer.disconnect();
      }
    });
  });
  io.observe(target);
};

images.forEach(lazyLoad);

// ====================== Dynamic Text Typing Effect ======================
const typeText = (element, text, delay) => {
  let index = 0;
  const interval = setInterval(() => {
    element.textContent += text[index];
    index++;
    if (index === text.length) {
      clearInterval(interval);
    }
  }, delay);
};

const taglineElement = document.querySelector(".nitro-tagline");
typeText(taglineElement, "Experience the Future of Luxury!", 60);

// ====================== Glowing Button Animation ======================
const buttons = document.querySelectorAll(".nitro-btn-glow");

buttons.forEach((btn) => {
  btn.addEventListener("mouseenter", () => {
    btn.classList.add("btn-glow");
  });

  btn.addEventListener("mouseleave", () => {
    btn.classList.remove("btn-glow");
  });
});

// ====================== Dynamic Gradient Transition ======================
const heroSection = document.querySelector(".nitro-about-hero");

let colors = [
  "linear-gradient(to right, #0f0c29, #302b63, #24243e)",
  "linear-gradient(to right, #0099F7, #F11712)",
  "linear-gradient(to right, #6a11cb, #2575fc)",
  "linear-gradient(to right, #2E3192, #1BFFFF)",
];

let currentIndex = 0;

setInterval(() => {
  heroSection.style.background = colors[currentIndex];
  currentIndex = (currentIndex + 1) % colors.length;
}, 5000);

// ====================== Show Year in Footer ======================
const yearSpan = document.querySelector(".nitro-year");
if (yearSpan) {
  yearSpan.textContent = new Date().getFullYear();
}
