// ===================== Smooth Scroll to Section =====================
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      document.querySelector(this.getAttribute("href")).scrollIntoView({
        behavior: "smooth",
        block: "start",
      });
    });
  });
});

// ===================== GODMODE IMAGE HOVER ANIMATION =====================
const roomImgContainer = document.querySelector(".godmode-room-img-container");
roomImgContainer.addEventListener("mouseenter", () => {
  roomImgContainer.classList.add("godmode-border-glow");
});

roomImgContainer.addEventListener("mouseleave", () => {
  roomImgContainer.classList.remove("godmode-border-glow");
});

// ===================== Button Glow Effect =====================
const buttons = document.querySelectorAll(
  ".godmode-room-btn, .godmode-back-btn"
);

buttons.forEach((btn) => {
  btn.addEventListener("mouseenter", () => {
    btn.classList.add("godmode-btn-glow");
  });

  btn.addEventListener("mouseleave", () => {
    btn.classList.remove("godmode-btn-glow");
  });
});

// ===================== Add Nitro Glow to Image on Click =====================
const roomImg = document.querySelector(".godmode-room-img");
roomImg.addEventListener("click", () => {
  roomImg.classList.add("godmode-glow-click");
  setTimeout(() => {
    roomImg.classList.remove("godmode-glow-click");
  }, 500);
});

// ===================== Inject Dynamic CSS for Extra Effects =====================
const css = `
/* GODMODE Border Glow */
.godmode-border-glow {
  box-shadow: 0 0 25px rgba(0, 255, 255, 0.9), 0 0 45px rgba(0, 166, 255, 0.8);
  transform: scale(1.05);
}

/* Button Glow Effect */
.godmode-btn-glow {
  box-shadow: 0 0 25px rgba(0, 255, 255, 0.8), 0 0 40px rgba(0, 166, 255, 0.7);
  transform: scale(1.1);
}

/* Click Glow Animation */
.godmode-glow-click {
  animation: glow-pulse 0.5s ease-in-out;
}

@keyframes glow-pulse {
  0% {
      box-shadow: 0 0 20px rgba(0, 255, 255, 0.8), 0 0 30px rgba(0, 166, 255, 0.7);
  }
  50% {
      box-shadow: 0 0 35px rgba(0, 255, 255, 1), 0 0 50px rgba(0, 166, 255, 0.9);
  }
  100% {
      box-shadow: 0 0 20px rgba(0, 255, 255, 0.8), 0 0 30px rgba(0, 166, 255, 0.7);
  }
}
`;

// Add Dynamic Styles
const styleSheet = document.createElement("style");
styleSheet.type = "text/css";
styleSheet.innerText = css;
document.head.appendChild(styleSheet);
