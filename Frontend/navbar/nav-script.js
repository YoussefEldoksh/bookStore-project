let root_div = document.getElementById("root");

function createNavBar() {
  let nav_bar = `
    <nav>
        <ul id="nav-icons">
            <li><a href="./index.html"> <i class="fa-solid fa-house" ></i></a>
            <p>HOME</p>
            </li>
            <li class="guarded-btn"><a href="../cart-view/cart.html"><i class="fa-solid fa-cart-shopping"></i></a><p>CART</p></li>
            <li class="guarded-btn"><a href="./about.html"><i class="fa-solid fa-bookmark"></i></a><p>SAVED</p></li>

            <li><a href="./index.html"><i class="fa-solid fa-user"></i></a><p>ACCOUNT</p></li>
            <li ><a href="./contact.html"><i class="fa-solid fa-handshake"></i></a><p>CONTACT US</p></li>           
            <li class="guarded-btn"><a href="./contact.html"><i class="fa-solid fa-door-open"></i></a><p>LOGIN</p></li>

        </ul>   
    </nav>
    `;
  return nav_bar;
}

root_div.innerHTML += createNavBar();


document.addEventListener("DOMContentLoaded", () => {
  const guardBtn = document.querySelectorAll(".guarded-btn");
  guardBtn.forEach((btn) => {
    addEventListener("click", (e) => {
      e.preventDefault();
    const token = localStorage.getItem('authToken')
    console.log(token)
      if (!token) {
        const currentPage = window.location.pathname;
        window.location.href = `../login-view/login.html?redirect=${encodeURIComponent(
          currentPage
        )}`;
        return;
      }
    });
  });
});

