document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
    const togglePasswordBtn = document.getElementById("togglePassword");
    const passwordInput = form.querySelector('input[name="userpass"]');

    // Create a div to show messages if it doesn't exist
    let messageDiv = document.getElementById("form-message");
    if (!messageDiv) {
        messageDiv = document.createElement("div");
        messageDiv.id = "form-message";
        form.prepend(messageDiv);
    }

    // Toggle password visibility
    togglePasswordBtn.addEventListener("click", () => {
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            togglePasswordBtn.innerHTML =
                '<i class="fa-solid fa-eye" style="font-size: 11px"></i>';
        } else {
            passwordInput.type = "password";
            togglePasswordBtn.innerHTML =
                '<i class="fa-solid fa-eye-slash" style="font-size: 11px"></i>';
        }
    });

    // Email validation regex
    const isValidEmail = (email) => {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    };

    // Form submission
    form.addEventListener("submit", (e) => {
        e.preventDefault(); // Prevent default form submission

        // Get form values
        const firstname = form.querySelector('input[name="firstname"]').value.trim();
        const lastname = form.querySelector('input[name="lastname"]').value.trim();
        const email = form.querySelector('input[name="usermail"]').value.trim();
        const username = form.querySelector('input[name="username"]').value.trim();
        const password = passwordInput.value.trim();
        const city = form.querySelector('input[name="useraddress-city"]').value.trim();
        const street = form.querySelector('input[name="useraddress-street"]').value.trim();
        const apt = form.querySelector('input[name="useraddress-apt"]').value.trim();

        // Simple validation
        if (!firstname || !lastname || !email || !username || !password || !city || !street || !apt) {
            messageDiv.innerHTML = "<p style='color:red;'>All fields are required!</p>";
            return;
        }

        if (!isValidEmail(email)) {
            messageDiv.innerHTML = "<p style='color:red;'>Please enter a valid email address.</p>";
            return;
        }

        if (password.length < 8) {
            messageDiv.innerHTML = "<p style='color:red;'>Password must be at least 8 characters long.</p>";
            return;
        }

        // Prepare form data for AJAX
        const formData = new FormData(form);

        // Send AJAX request to backend
        fetch(form.action, {
            method: "POST",
            body: formData
        })
            .then(res => res.text())
            .then(data => {
                if (data.includes("Registration successful")) {
                    window.location.href = "../admin-page/adminPage.php"; // redirect to admin page
                } else {
                    messageDiv.innerHTML = data; // show backend error
                }
            })
            .catch(err => {
                console.error(err);
                messageDiv.innerHTML = "<p style='color:red;'>An error occurred. Please try again.</p>";
            });
    });
});

