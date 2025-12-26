document.addEventListener("DOMContentLoaded", () => {

    // --- LOGOUT HANDLING ---
    const logoutLink = document.getElementById("logout-link");
    const logoutModal = document.getElementById("logout-modal");
    const logoutYes = document.getElementById("logout-yes");
    const logoutNo = document.getElementById("logout-no");

    // Show logout confirmation modal
    logoutLink.addEventListener("click", (e) => {
        e.preventDefault();
        logoutModal.style.display = "flex";
    });

    // Hide logout modal if "No" is clicked
    logoutNo.addEventListener("click", () => {
        logoutModal.style.display = "none";
    });

    // Proceed to logout if "Yes" is clicked
    logoutYes.addEventListener("click", () => {
        window.location.href = "../admin-login-view/login.php";
    });

    // --- FORM HANDLING ---
    const form = document.querySelector("form");

    if (!form) {
        console.error('Form not found!');
        return;
    }

    // Get form input fields
    const firstNameField = document.querySelector('input[name="firstname"]');
    const lastNameField = document.querySelector('input[name="lastname"]');
    const emailField = document.querySelector('input[name="usermail"]');
    const usernameField = document.querySelector('input[name="username"]');
    const cityField = document.querySelector('input[name="useraddress-city"]');
    const streetField = document.querySelector('input[name="useraddress-street"]');
    const apartmentField = document.querySelector('input[name="useraddress-apt"]');

    // --- Form Submission Handling ---
    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        // Get values from the form fields
        const firstName = firstNameField.value.trim();
        const lastName = lastNameField.value.trim();
        const email = emailField.value.trim();
        const password = document.querySelector('input[name="userpass"]').value;
        const username = usernameField.value.trim();
        const city = cityField.value.trim();
        const street = streetField.value.trim();
        const apartment = apartmentField.value.trim();

        // Form validation (ensure all fields are filled)
        if (
            firstName === "" ||
            lastName === "" ||
            email === "" ||
            city === "" ||
            username === "" ||
            street === "" ||
            apartment === ""
        ) {
            alert("All fields must be filled out!");
            return;
        }

        // Send form data to the server using fetch
        try {
            const response = await fetch("./edit-Profile.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `firstname=${encodeURIComponent(firstName)}&lastname=${encodeURIComponent(lastName)}&usermail=${encodeURIComponent(email)}&username=${encodeURIComponent(username)}&userpass=${encodeURIComponent(password)}&useraddress-city=${encodeURIComponent(city)}&useraddress-street=${encodeURIComponent(street)}&useraddress-apt=${encodeURIComponent(apartment)}&submit=1`,
            });

            const result = await response.json(); // Parse JSON response

            if (result.success) {
                alert(result.success); // Success message
                window.location.reload(); // Optionally reload the page to reflect changes
            } else {
                alert(result.error); // Show error message
            }
        } catch (error) {
            console.error("Error:", error);
            alert("An error occurred. Please try again.");
        }
    });
});
