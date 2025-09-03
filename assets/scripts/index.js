function toggleMobileMenu() {
  const menu = document.getElementById("mobileMenu");
  menu.classList.toggle("d-none");
}

const observerOptions = {
  threshold: 0.1,
  rootMargin: "0px 0px -50px 0px",
};

const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.style.opacity = "1";
      entry.target.style.transform = "translateY(0)";
    }
  });
}, observerOptions);

document.querySelectorAll(".service-card").forEach((card) => {
  card.style.opacity = "0";
  card.style.transform = "translateY(20px)";
  card.style.transition = "opacity 0.6s ease, transform 0.6s ease";
  observer.observe(card);
});

window.addEventListener("scroll", () => {
  const scrolled = window.pageYOffset;
  const rate = scrolled * -0.5;

  document.querySelectorAll(".red-circle").forEach((circle) => {
    circle.style.transform = `translateY(${rate}px)`;
  });
});

function handleSubmit(event) {
  event.preventDefault();
  const email = event.target.querySelector('input[type="email"]').value;
  alert(`Thank you for subscribing with email: ${email}`);
  event.target.reset();
}

document.querySelectorAll(".footer-section a").forEach((link) => {
  link.addEventListener("mouseenter", function () {
    this.style.transform = "translateX(5px)";
    this.style.transition = "transform 0.3s ease";
  });

  link.addEventListener("mouseleave", function () {
    this.style.transform = "translateX(0)";
  });
});
