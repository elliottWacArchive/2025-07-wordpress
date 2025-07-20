document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const sidebarToggle = document.querySelector(".sidebar-toggle");
    const closeSidebar = document.querySelector(".close-sidebar");
    const sidebarOverlay = document.querySelector(".sidebar-overlay");
    const body = document.body;

    function openSidebar() {
        sidebar.classList.add("active");
        sidebarToggle.classList.add("active");
        body.classList.add("sidebar-open");

        const widgets = document.querySelectorAll(".widget");
        widgets.forEach((widget, index) => {
            setTimeout(() => {
                widget.style.opacity = "1";
                widget.style.transform = "translateX(0)";
            }, index * 100);
        });
    }

    function closeSidebarFunction() {
        sidebar.classList.remove("active");
        sidebarToggle.classList.remove("active");
        body.classList.remove("sidebar-open");

        const widgets = document.querySelectorAll(".widget");
        widgets.forEach((widget) => {
            widget.style.opacity = "0";
            widget.style.transform = "translateX(-30px)";
        });
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener("click", function (e) {
            e.preventDefault();
            if (sidebar.classList.contains("active")) {
                closeSidebarFunction();
            } else {
                openSidebar();
            }
        });
    }

    if (closeSidebar) {
        closeSidebar.addEventListener("click", function (e) {
            e.preventDefault();
            closeSidebarFunction();
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener("click", function () {
            closeSidebarFunction();
        });
    }

    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape" && sidebar.classList.contains("active")) {
            closeSidebarFunction();
        }
    });

    const widgets = document.querySelectorAll(".widget");
    widgets.forEach((widget) => {
        widget.style.opacity = "0";
        widget.style.transform = "translateX(-30px)";
        widget.style.transition = "all 0.3s ease";
    });

    const menuLinks = document.querySelectorAll(".sidebar-menu a");
    menuLinks.forEach((link) => {
        link.addEventListener("mouseenter", function () {
            this.style.transform = "translateX(10px)";
        });

        link.addEventListener("mouseleave", function () {
            this.style.transform = "translateX(0)";
        });
    });

    if (sidebarToggle) {
        sidebarToggle.addEventListener("mouseenter", function () {
            this.style.transform = "scale(1.1) rotate(5deg)";
        });

        sidebarToggle.addEventListener("mouseleave", function () {
            this.style.transform = "scale(1) rotate(0deg)";
        });
    }
});

const additionalStyles = `
    .animate-in {
        opacity: 1 !important;
        transform: translateX(0) !important;
    }
    
    .widget {
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }
    
    @media (prefers-reduced-motion: reduce) {
        .miku-sidebar,
        .widget,
        .sidebar-toggle,
        .social-link {
            transition: none;
            animation: none;
        }
    }
`;

const styleSheet = document.createElement("style");
styleSheet.textContent = additionalStyles;
document.head.appendChild(styleSheet);
