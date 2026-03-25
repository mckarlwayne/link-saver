import "./bootstrap";

// Modal functions
function openModal(modalId) {
    document.getElementById(modalId).classList.remove("hidden");
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add("hidden");
}

// Event listeners for modal triggers
document.addEventListener("DOMContentLoaded", function () {
    const loginBtn = document.querySelector('[data-bs-target="#loginModal"]');
    const registerBtn = document.querySelector(
        '[data-bs-target="#registerModal"]',
    );
    const logoutBtn = document.querySelector('[data-bs-target="#logoutModal"]');

    if (loginBtn)
        loginBtn.addEventListener("click", () => openModal("loginModal"));
    if (registerBtn)
        registerBtn.addEventListener("click", () => openModal("registerModal"));
    if (logoutBtn)
        logoutBtn.addEventListener("click", () => openModal("logoutModal"));
});

// Register Form Handler
document
    .getElementById("registerForm")
    .addEventListener("submit", async function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const data = Object.fromEntries(formData);

        try {
            const response = await fetch("/register", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'input[name="_token"]',
                    ).value,
                },
                body: JSON.stringify(data),
            });

            if (response.ok) {
                // Clear form
                document.getElementById("registerForm").reset();

                // Close modal
                closeModal("registerModal");

                // Show success alert
                showAlert(
                    "Success! You have been registered successfully. You can now use the app!",
                    "success",
                );
            } else {
                const errors = await response.json();
                showErrorMessages(errors.errors);
            }
        } catch (error) {
            showAlert("An error occurred. Please try again.", "danger");
        }
    });

function showAlert(message, type) {
    const alertDiv = document.createElement("div");
    alertDiv.className = `fixed top-4 right-4 z-50 p-4 rounded-md shadow-lg ${type === "success" ? "bg-green-500 text-white" : "bg-red-500 text-white"}`;
    alertDiv.innerHTML = `
        <div class="flex items-center">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <i class="bi bi-x"></i>
            </button>
        </div>
    `;
    document.body.appendChild(alertDiv);

    // Auto dismiss after 2.5 seconds
    setTimeout(() => {
        alertDiv.classList.add("opacity-0");
        setTimeout(() => {
            if (alertDiv.parentElement) alertDiv.remove();
        }, 300);
    }, 2500);
}

function showErrorMessages(errors) {
    // Clear previous errors
    document
        .querySelectorAll(".text-red-500")
        .forEach((el) => el.classList.add("hidden"));

    // Show new errors
    if (errors.name) {
        document.getElementById("nameError").textContent = errors.name[0];
        document.getElementById("nameError").classList.remove("hidden");
    }
    if (errors.email) {
        document.getElementById("emailError").textContent = errors.email[0];
        document.getElementById("emailError").classList.remove("hidden");
    }
    if (errors.password) {
        document.getElementById("passwordError").textContent =
            errors.password[0];
        document.getElementById("passwordError").classList.remove("hidden");
    }
}

// Login Form Handler
document
    .getElementById("loginForm")
    .addEventListener("submit", async function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const data = Object.fromEntries(formData);

        try {
            const response = await fetch("/login", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'input[name="_token"]',
                    ).value,
                },
                body: JSON.stringify(data),
            });

            if (response.ok) {
                // Clear form
                document.getElementById("loginForm").reset();

                // Close modal
                closeModal("loginModal");

                // Show welcome toast
                showAlert(
                    "Welcome back! Redirecting to your dashboard...",
                    "success",
                );

                // Redirect after 2.5 seconds so the message is visible
                setTimeout(() => {
                    window.location.href = "/links";
                }, 2500);
            } else {
                showAlert("Invalid email or password", "danger");
            }
        } catch (error) {
            showAlert("An error occurred. Please try again.", "danger");
        }
    });

// Logout Handler
document
    .getElementById("logoutBtn")
    .addEventListener("click", async function () {
        try {
            const response = await fetch("/logout", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'input[name="_token"]',
                    ).value,
                },
            });

            if (response.ok) {
                // Redirect to login page
                window.location.href = "/";
            } else {
                showAlert("An error occurred during logout.", "danger");
            }
        } catch (error) {
            showAlert("An error occurred. Please try again.", "danger");
        }
    });

// Profile dropdown toggle and outside click guard
const userMenuButton = document.getElementById("userMenuButton");
const userMenuDropdown = document.getElementById("userMenuDropdown");

if (userMenuButton && userMenuDropdown) {
    userMenuButton.addEventListener("click", (event) => {
        event.stopPropagation();
        userMenuDropdown.classList.toggle("hidden");
    });

    document.addEventListener("click", (event) => {
        if (!event.target.closest("#userMenuWrapper")) {
            userMenuDropdown.classList.add("hidden");
        }
    });
}

const welcomeAlert = document.getElementById("welcomeAlert");
if (welcomeAlert) {
    setTimeout(() => {
        welcomeAlert.classList.add("opacity-0");
        setTimeout(() => welcomeAlert.remove(), 300);
    }, 2500);
}
