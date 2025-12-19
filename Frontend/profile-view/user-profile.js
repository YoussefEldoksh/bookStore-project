document.addEventListener("DOMContentLoaded", (e) => {
  const firstName = document.querySelector('input[name="firstname"]');
  const lastName = document.querySelector('input[name="lastname"]');
  const email = document.querySelector('input[name="usermail"]');
  const password = document.querySelector('input[name="userpass"]');
  const username = document.querySelector('input[name="username"]');
  const city = document.querySelector('input[name="useraddress-city"]');
  const street = document.querySelector('input[name="useraddress-street"]');
  const apartment = document.querySelector('input[name="useraddress-apt"]');

  firstName.value = localStorage.getItem("firstname");
  lastName.value = localStorage.getItem("lastname");
  email.value = localStorage.getItem("email");
  username.value = localStorage.getItem("username");
  city.value = localStorage.getItem("city");
  street.value = localStorage.getItem("street");
  apartment.value = localStorage.getItem("apartment");

  const form = document.querySelector("form");

  if (!form) {
    console.error('Form with class "login-form" not found!');
    return;
  }

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const firstName = document
      .querySelector('input[name="firstname"]')
      .value.trim();
    const lastName = document
      .querySelector('input[name="lastname"]')
      .value.trim();
    const email = document.querySelector('input[name="usermail"]').value.trim();
    const password = document.querySelector('input[name="userpass"]').value;
    const username = document.querySelector('input[name="username"]').value;
    const city = document
      .querySelector('input[name="useraddress-city"]')
      .value.trim();
    const street = document
      .querySelector('input[name="useraddress-street"]')
      .value.trim();
    const apartment = document
      .querySelector('input[name="useraddress-apt"]')
      .value.trim();

    // Validate form
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

    try {
      const response = await fetch("editprofile.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `firstname=${encodeURIComponent(firstName)}&lastname=${encodeURIComponent(lastName)}&usermail=${encodeURIComponent(email)}&username=${encodeURIComponent(username)}&userpass=${encodeURIComponent(password)}&useraddress-city=${encodeURIComponent(city)}&useraddress-street=${encodeURIComponent(street)}&useraddress-apt=${encodeURIComponent(apartment)}&submit=1`,
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


    } catch (error) {
        console.error("JSON parse error:", error);
        alert("Invalid server response. Check browser console.");
        return;
    }
  });
});
