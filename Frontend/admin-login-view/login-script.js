document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("form");
  const passwordInput = form.querySelector('input[name="userpass"]');
  const togglePasswordBtn = document.querySelector(".toggle-password");

  // Toggle password visibility
  togglePasswordBtn.addEventListener("click", () => {
    passwordInput.type =
      passwordInput.type === "password" ? "text" : "password";
  });

  const isValidEmail = (email) =>
    /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

  // Client-side validation ONLY
  form.addEventListener("submit", (e) => {
    const email = form.querySelector('input[name="usermail"]').value.trim();
    const password = passwordInput.value.trim();

    if (!email || !password) {
      alert("All fields are required!");
      e.preventDefault();
      return;
    }

    if (!isValidEmail(email)) {
      alert("Please enter a valid email address.");
      e.preventDefault();
      return;
    }

    if (password.length < 8) {
      alert("Password must be at least 8 characters long.");
      e.preventDefault();
      return;
    }
  });
});

