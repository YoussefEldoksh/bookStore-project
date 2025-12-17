let root_div = document.getElementById("root");

function createNavBar() {
  let nav_bar = `
    <nav>
        <ul id="nav-icons">
            <li><a href="../home-page/index.html"> <i class="fa-solid fa-house" ></i></a>
            <p>HOME</p>
            </li>
            <li class="guarded-btn"><a href="../cart-view/cart.html"><i class="fa-solid fa-cart-shopping"></i></a><p>CART</p></li>
            <li class="guarded-btn"><a href="./about.html"><i class="fa-solid fa-bookmark"></i></a><p>SAVED</p></li>

            <li class="guarded-btn"><a href="../profile-view/user-profile.html"><i class="fa-solid fa-user"></i></a><p>ACCOUNT</p></li>
            <li ><a href="./contact.html"><i class="fa-solid fa-handshake"></i></a><p>CONTACT US</p></li>           
            <li class="login-btn"><i class="fa-solid fa-door-open"></i><p class="door-btn">LOGOUT</p></li>

        </ul>   
    </nav>
    `;
  return nav_bar;
}

root_div.innerHTML += createNavBar();

document.addEventListener("DOMContentLoaded", () => {
  const guardBtn = document.querySelectorAll(".guarded-btn");
  guardBtn.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      console.log("Guarded button clicked");
      const token = localStorage.getItem("authToken");
      console.log("Token:", token);

      if (!token) {
        console.log("No token, redirecting to login...");
        e.preventDefault();
        e.stopPropagation(); // Stop bubbling just in case
        const login_btn = document.querySelector(".door-btn");
        login_btn.innerHTML = "LOGOUT";
        const currentPage = window.location.pathname;
        window.location.href = `../login-view/login.html?redirect=${encodeURIComponent(
          currentPage
        )}`;
        return;
      }
    });
  });
  const login_btn = document.querySelector(".login-btn");

  login_btn.addEventListener("click", (e) => {
    const token = localStorage.getItem("authToken");
    if (!token) {
      console.log("No token, redirecting to login...");
      e.preventDefault();
      e.stopPropagation(); // Stop bubbling just in case
      const currentPage = window.location.pathname;
      window.location.href = `../login-view/login.html?redirect=${encodeURIComponent(
        currentPage
      )}`;
    } else {
      localStorage.clear();
      window.location.href = `../login-view/login.html`;
    }
  });
});
