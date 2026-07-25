const header = document.querySelector(".site-header");
const loginButton = document.querySelector("#loginBtn");
const profilePopup = document.querySelector("#profilePopup");
const tanggal = document.querySelector("#tanggal");

if (tanggal) {
    tanggal.textContent = new Date().toLocaleDateString("id-ID", {
        weekday: "long",
        day: "numeric",
        month: "long",
        year: "numeric"
    });
}

if (header) {
    window.addEventListener("scroll", () => {
        header.classList.toggle("is-scrolled", window.scrollY > 50);
    });
}

if (loginButton && profilePopup) {
    loginButton.addEventListener("click", (event) => {
        event.stopPropagation();
        const isOpen = profilePopup.classList.toggle("is-open");
        profilePopup.setAttribute("aria-hidden", String(!isOpen));
    });

    document.addEventListener("click", (event) => {
        if (!profilePopup.contains(event.target) && !loginButton.contains(event.target)) {
            profilePopup.classList.remove("is-open");
            profilePopup.setAttribute("aria-hidden", "true");
        }
    });
}

const topButton = document.createElement("button");
topButton.id = "topButton";
topButton.type = "button";
topButton.textContent = "^";
topButton.setAttribute("aria-label", "Kembali ke atas");
document.body.appendChild(topButton);

window.addEventListener("scroll", () => {
    topButton.style.display = window.scrollY > 300 ? "block" : "none";
});

topButton.addEventListener("click", () => {
    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });
});

const cards = document.querySelectorAll(".card, .side-card, .popular-card, .video-card");

if ("IntersectionObserver" in window) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add("is-visible");
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.12
    });

    cards.forEach((card) => observer.observe(card));
} else {
    cards.forEach((card) => card.classList.add("is-visible"));
}

document.querySelectorAll("form").forEach((form) => {
    form.addEventListener("submit", (event) => {
        event.preventDefault();

        if (form.closest(".contact-form")) {
            alert("Pesan berhasil dikirim. Terima kasih telah menghubungi kami.");
        } else if (form.closest(".newsletter")) {
            alert("Terima kasih sudah berlangganan newsletter PortalBerita.");
        }

        form.reset();
    });
});

const currentPage = location.pathname.split("/").pop() || "index.html";

document.querySelectorAll(".navbar a").forEach((link) => {
    const href = link.getAttribute("href");

    if (href === currentPage) {
        link.classList.add("active");
    }
});

document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", (event) => {
        const selector = anchor.getAttribute("href");

        if (!selector || selector === "#") {
            return;
        }

        const target = document.querySelector(selector);

        if (target) {
            event.preventDefault();
            target.scrollIntoView({ behavior: "smooth" });
        }
    });
});
