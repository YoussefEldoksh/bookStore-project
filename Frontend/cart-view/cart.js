function createCards() {
  let booksHTML = "";
  let subTotal = 0;

  // Sample data - you should replace this with actual cart data
  const cartItems = JSON.parse(localStorage.getItem("cartArray"));

  cartItems.forEach((item) => {
    subTotal += parseFloat(item.price) * parseFloat(item.quantity);
    booksHTML += `
                    <div class="order-content" data-id="${
                      item.bookId
                    }" data-price="${item.price}">
                        <div class="order-item">
                            <img src="${item.bookImage}" alt="${item.bookTitle}">
                        </div>
                        <div class="item-info">
                            <div class="item-title">
                                <p style="font-size: 18px; margin-top: 5%; text-align: left">${
                                  item.bookTitle
                                }</p>
                                <p style="font-size: 18px; margin-top: 6%">$${parseFloat(item.price).toFixed(
                                  2
                                )}</p>
                            </div>
                            <div class="item-footer">
                                <div class="quantity-control">
                                    <button class="quantity-btn-decrease" data-id="${
                                      item.bookId
                                    }">−</button>
                                    <span class="quantity-value" data-id="${
                                      item.bookId
                                    }">${item.quantity}</span>
                                    <button class="quantity-btn-increase" data-id="${
                                      item.bookId
                                    }">+</button>
                                </div>
                                <div class="remove-btn-container">
                                    <button class="remove-btn" data-id="${
                                      item.bookId
                                    }">remove</button>
                                </div>
                            </div>
                        </div>
                    </div>
        `;
  });

  let deliveryCost = 10;

  booksHTML += `
                    <div class="divider-container">
                        <div class="divider-line"></div>
                        <span>ORDER TOTAL</span>
                        <div class="divider-line"></div>
                    </div>
                  
                    <div class="total-cost">
                        <div class="sub-cost">
                            <p class="sub-cost-name">Subtotal</p>
                            <p class="sub-cost-amount" id="subtotal">$${parseFloat(
                              subTotal
                            ).toFixed(2)}</p>
                        </div>
                        <div class="sub-cost">
                            <p class="sub-cost-name">Delivery cost</p>
                            <p class="sub-cost-amount">$${parseFloat(
                              deliveryCost
                            ).toFixed(2)}</p>
                        </div>
                        <div class="sub-cost">
                            <p class="sub-cost-name">Discount</p>
                            <p class="sub-cost-amount">$0.00</p>
                        </div>
                        <div class="sub-cost">
                            <p class="sub-cost-name" style="font-size: 18px;">Total to pay</p>
                            <p class="sub-cost-amount" id="total" style="font-size: 18px;">$${(
                              parseFloat(subTotal) + parseFloat(deliveryCost)
                            ).toFixed(2)}</p>
                        </div>
                    </div>
    `;

  return booksHTML;
}

function updateTotals() {
  let newSubTotal = 0;
  const cartItems = document.querySelectorAll(".order-content");

  cartItems.forEach((item) => {
    const price = parseFloat(item.dataset.price);
    const quantity = parseInt(
      item.querySelector(".quantity-value").textContent
    );
    newSubTotal += price * quantity;
  });

  const deliveryCost = 10;
  const total = newSubTotal + deliveryCost;

  document.getElementById("subtotal").textContent = `$${newSubTotal.toFixed(
    2
  )}`;
  document.getElementById("total").textContent = `$${total.toFixed(2)}`;
}

document.addEventListener("DOMContentLoaded", function () {
  let right_div = document.querySelector(".right-div");
  right_div.innerHTML += createCards();

  // Event listener for increase quantity buttons
  document.addEventListener("click", function (e) {
    if (e.target.classList.contains("quantity-btn-increase")) {
      const itemId = e.target.dataset.id;
      const quantitySpan = document.querySelector(
        `.quantity-value[data-id="${itemId}"]`
      );
      let currentQty = parseInt(quantitySpan.textContent);
      quantitySpan.textContent = currentQty + 1;
      updateTotals();
    }
  });

  // Event listener for decrease quantity buttons
  document.addEventListener("click", function (e) {
    if (e.target.classList.contains("quantity-btn-decrease")) {
      const itemId = e.target.dataset.id;
      const quantitySpan = document.querySelector(
        `.quantity-value[data-id="${itemId}"]`
      );
      let currentQty = parseInt(quantitySpan.textContent);
      if (currentQty > 1) {
        quantitySpan.textContent = currentQty - 1;
        updateTotals();
      }
    }
  });

  // Event listener for remove buttons
  document.addEventListener("click", function (e) {
    if (e.target.classList.contains("remove-btn")) {
      const itemId = e.target.dataset.id;
      const cartItem = document.querySelector(
        `.order-content[data-id="${itemId}"]`
      );
      cartItem.style.transition = "opacity 0.3s ease";
      cartItem.style.opacity = "0";
      setTimeout(() => {
        cartItem.remove();
        updateTotals();
      }, 300);
    }
  });
  let form = document.querySelector(".myorder-form");
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const firstName = document
      .querySelector('input[name="firstname"]')
      .value.trim();
    const lastName = document
      .querySelector('input[name="lastname"]')
      .value.trim();
    const email = document.querySelector('input[name="usermail"]').value.trim();
    const cardNumber = document.querySelector(
      'input[name="card-number"]'
    ).value;
    const cardCvv = document.querySelector('input[name="card-cvv"]').value;
    const cardHolderName = document.querySelector(
      'input[name="card-holder-name"]'
    ).value;
    const expiryDate = document.querySelector(
      'input[name="expiry-date"]'
    ).value;

    if (
      (firstName === "") |
      (lastName === "") |
      (email === "") |
      (cardNumber === "") |
      (cardCvv === "") |
      (cardHolderName === "") |
      (expiryDate === "")
    ) {
      alert("All fields must be filled out!");
      return;
    }

    if (!isValidEmail(email)) {
      alert("Invalid email address!");
      return;
    }
    if (cardNumber.length < 13 || cardNumber.length > 19) {
      alert("Card number must be 13-19 digits!");
      return;
    }
    if (!/^\d+$/.test(cardNumber)) {
      alert("Card number must contain only digits!");
      return;
    }
    if (!/^\d{3,4}$/.test(cardCvv)) {
      alert("CVV must be 3-4 digits!");
      return;
    }

    if (!/^\d{2}\/\d{2}$/.test(expiryDate)) {
      alert("Expiry date must be in MM/YY format!");
      return;
    }

    if (isCardExpired(expiryDate)) {
      alert("Card has expired!");
      return;
    }
    if (!luhnAlgoForCheckingCcNumber(cardNumber)) {
      alert("Invalid card information !");
      return;
    }

    if (cardHolderName.length < 3) {
      alert("Cardholder name must be at least 3 characters!");
      return;
    }

//     cardNumber
// cardCvv
// cardHolderName
// expiryDate
    try {
      const result = await fetch("order.php", {
                  method: "POST",
          headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },

        body: `firstname=${encodeURIComponent(firstName)}&lastname=${encodeURIComponent(lastName)}&usermail=${encodeURIComponent(email)}&username=${encodeURIComponent(username)}&cardNumber=${encodeURIComponent(cardNumber)}&cardCvv=${encodeURIComponent(cardCvv)}&cardHolderName=${encodeURIComponent(cardHolderName)}&expiryDate=${encodeURIComponent(expiryDate)}&submit=1`,
      });

      console.log("Response status:", response.status);

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const text = await response.text();
      console.log("Raw response:", text);
      
    } catch (error) {
      
    }



    alert("Order submitted successfully!");

  });

  function luhnAlgoForCheckingCcNumber(number) {
    let digits = number.toString().split("").map(Number);
    let sum = 0;
    let isSecond = false;

    for (let i = digits.length - 1; i >= 0; i--) {
      let digit = digits[i];

      if (isSecond) {
        digit *= 2;
        if (digit > 9) {
          digit -= 9;
        }
      }

      sum += digit;
      isSecond = !isSecond;
    }

    return sum % 10 === 0;
  }

  function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  function isCardExpired(expiryString) {
    const [month, year] = expiryString.split("/");
    const expiryDate = new Date(2000 + parseInt(year), parseInt(month), 0);
    return expiryDate < new Date();
  }
});
