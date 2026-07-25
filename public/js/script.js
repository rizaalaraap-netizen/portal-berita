const header = document.querySelector(".site-header");
const loginButton = document.querySelector("#loginBtn");
const profilePopup = document.querySelector("#profilePopup");
const tanggal = document.querySelector("#tanggal");
const menuButton = document.querySelector(".menu-btn");
const provinceOverlay = document.querySelector("#provinceOverlay");
const provinceSearch = document.querySelector("#provinceSearch");
const provinceMessage = document.querySelector(".province-message");
const provinceTooltip = document.querySelector(".province-tooltip");
const provinceTargets = document.querySelectorAll(".province-map-link, .province-item");

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

if (menuButton && provinceOverlay) {
    const closeButtons = provinceOverlay.querySelectorAll("[data-close-province]");
    const panel = provinceOverlay.querySelector(".province-panel");

    const showProvinceMessage = (message) => {
        if (!provinceMessage) {
            return;
        }

        provinceMessage.textContent = message;
        provinceMessage.classList.add("is-visible");
    };

    const clearProvinceMessage = () => {
        if (!provinceMessage) {
            return;
        }

        provinceMessage.textContent = "";
        provinceMessage.classList.remove("is-visible");
    };

    const openProvinceOverlay = () => {
        provinceOverlay.classList.add("is-open");
        provinceOverlay.setAttribute("aria-hidden", "false");
        menuButton.setAttribute("aria-expanded", "true");
        document.body.classList.add("province-overlay-open");
        clearProvinceMessage();

        if (provinceSearch) {
            provinceSearch.value = "";
            provinceTargets.forEach((target) => target.classList.remove("is-hidden"));
            window.setTimeout(() => provinceSearch.focus(), 120);
        }
    };

    const closeProvinceOverlay = () => {
        provinceOverlay.classList.remove("is-open");
        provinceOverlay.setAttribute("aria-hidden", "true");
        menuButton.setAttribute("aria-expanded", "false");
        document.body.classList.remove("province-overlay-open");
        clearProvinceMessage();

        if (provinceTooltip) {
            provinceTooltip.classList.remove("is-visible");
            provinceTooltip.setAttribute("aria-hidden", "true");
        }
    };

    const activateProvince = (target, event) => {
        const province = target.dataset.province || "Provinsi";
        const count = Number(target.dataset.count || 0);
        const url = target.dataset.url || target.getAttribute("href");

        if (count <= 0) {
            event.preventDefault();
            showProvinceMessage(`Belum ada berita dari provinsi ini: ${province}.`);
            return;
        }

        if (url) {
            event.preventDefault();
            window.location.href = url;
        }
    };

    const setActiveProvince = (province) => {
        provinceTargets.forEach((target) => {
            target.classList.toggle("is-active", target.dataset.province === province);
        });
    };

    const moveTooltip = (event, target) => {
        if (!provinceTooltip || !panel) {
            return;
        }

        const count = Number(target.dataset.count || 0);
        const province = target.dataset.province || "Provinsi";
        const rect = panel.getBoundingClientRect();

        provinceTooltip.innerHTML = `<strong>${province}</strong><br>${count} berita`;
        provinceTooltip.style.left = `${event.clientX - rect.left}px`;
        provinceTooltip.style.top = `${event.clientY - rect.top}px`;
        provinceTooltip.classList.add("is-visible");
        provinceTooltip.setAttribute("aria-hidden", "false");
    };

    menuButton.addEventListener("click", openProvinceOverlay);

    closeButtons.forEach((button) => {
        button.addEventListener("click", closeProvinceOverlay);
    });

    provinceTargets.forEach((target) => {
        target.addEventListener("click", (event) => activateProvince(target, event));

        target.addEventListener("mouseenter", (event) => {
            setActiveProvince(target.dataset.province);
            moveTooltip(event, target);
        });

        target.addEventListener("mousemove", (event) => moveTooltip(event, target));

        target.addEventListener("mouseleave", () => {
            target.classList.remove("is-active");

            if (provinceTooltip) {
                provinceTooltip.classList.remove("is-visible");
                provinceTooltip.setAttribute("aria-hidden", "true");
            }
        });

        target.addEventListener("focus", () => {
            setActiveProvince(target.dataset.province);
        });

        target.addEventListener("blur", () => {
            target.classList.remove("is-active");
        });
    });

    if (provinceSearch) {
        provinceSearch.addEventListener("input", () => {
            const keyword = provinceSearch.value.trim().toLowerCase();
            clearProvinceMessage();

            provinceTargets.forEach((target) => {
                const province = (target.dataset.province || "").toLowerCase();
                target.classList.toggle("is-hidden", Boolean(keyword) && !province.includes(keyword));
            });
        });
    }

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape" && provinceOverlay.classList.contains("is-open")) {
            closeProvinceOverlay();
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

document.querySelectorAll(".newsletter form").forEach((form) => {
    form.addEventListener("submit", (event) => {
        event.preventDefault();

        alert("Terima kasih sudah berlangganan newsletter PortalBerita.");
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
