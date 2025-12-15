let root = document.querySelector("#root");

document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector(".login-form");

  if (!form) {
    console.error('Form with class "login-form" not found!');
    return;
  }

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const firstName = document.querySelector('input[name="firstname"]').value.trim();
    const lastName = document.querySelector('input[name="lastname"]').value.trim();
    const email = document.querySelector('input[name="usermail"]').value.trim();
    const password = document.querySelector('input[name="userpass"]').value;
    const username = document.querySelector('input[name="username"]').value;
    const shippingAddress = document.querySelector('input[name="useraddress"]').value.trim();

    // Validate form
    if (firstName === "" || lastName === "" || email === "" || password === "" || shippingAddress === "" || username === "") {
      alert("All fields must be filled out!");
      return;
    }

    // Check password length
    if (password.length < 8) {
      alert("Password must be at least 8 characters long!");
      return;
    }

    try {
      console.log("Sending registration request...");

      const response = await fetch("signup.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `firstname=${encodeURIComponent(firstName)}&lastname=${encodeURIComponent(lastName)}&usermail=${encodeURIComponent(email)}&username=${encodeURIComponent(username)}&userpass=${encodeURIComponent(password)}&useraddress=${encodeURIComponent(shippingAddress)}&submit=1`,
      });

      console.log("Response status:", response.status);

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const text = await response.text();
      console.log("Raw response:", text);

      if (!text || text.length === 0) {
        alert("Server returned empty response. Check PHP file.");
        return;
      }

      let data;
      try {
        data = JSON.parse(text);
      } catch (parseError) {
        console.error("JSON parse error:", parseError);
        console.error("Response was:", text);
        alert("Invalid server response. Check browser console.");
        return;
      }

      console.log("Parsed data:", data);

      if (data.success) {
        // Store token and user data
        localStorage.setItem("authToken", data.token);
        localStorage.setItem("user_id", data.user_id);
        localStorage.setItem("username", data.username);
        localStorage.setItem("userType", data.type || "Customer");
        localStorage.setItem("useraddress", shippingAddress);

        alert("Registration successful!");

        // Redirect after storing data
        setTimeout(() => {
          window.location.href = "../home-page/index.html";
        }, 500);
      } else {
        alert(data.message || "Registration failed");
      }
    } catch (error) {
      console.error("Error:", error);
      alert("Registration failed: " + error.message);
    }
  });
});