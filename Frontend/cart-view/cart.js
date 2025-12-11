

let root = document.getElementById("root");

function createCartPage(){
    let cart = `
        <nav>
        <ul id="nav-icons">
            <li><a href="../index.html"> <i class="fa-solid fa-house" ></i></a>
            <p>Home</p>
            </li>
            <li><a href="..//about.html"><i class="fa-solid fa-layer-group"></i></a><p>Categories</p></li>
            <li><a href="../cart-view/cart.html"><i class="fa-solid fa-cart-shopping"></i></a><p>CART</p></li>
            <li><a href="./about.html"><i class="fa-solid fa-bookmark"></i></a><p>Saved</p></li>

        </ul> 
        
        <ul id="nav-links">
            <li><a href="./index.html"><i class="fa-solid fa-user"></i></a><p>Account</p></li>
            <li><a href="./contact.html"><i class="fa-solid fa-handshake"></i></a><p>Contact Us</p></li>            <li><a href="./contact.html"><i class="fa-solid fa-door-open"></i></a><p>Logout</p></li>

        </ul>   
    </nav>
        <div class="cart-page">
            <div class="input-container">
                <a href="./index.html"><i class="fa-solid fa-magnifying-glass"></i></a>
                <input
                    class="input"
                    name="text"
                    type="text"
                    placeholder="Title, author, host, or topic..."/>
            </div>

            <div class="cart-page-inner">
                <div class="payment-container">
                </div>

                <div class="items-added">
                    <div class="cart-title-div">
                        <p class="cart-title" style="font-family: 'Courier New', monospace;">My Cart</p>
                    </div>   
                    ${createCards()} 
                </div>
            </div>
        </div>
    `;
    return cart;
}

function createCards(){
    let booksHTML = "";
    
    // Sample data - you should replace this with actual cart data
    const cartItems = [
        { id: 1, title: "Storytelling in design", author: "Anna Dalstrom", price: 80.00, image: "../assets/The-great-gatsby.jpg", quantity: 1 },
        { id: 2, title: "The Great Gatsby", author: "F. Scott Fitzgerald", price: 15.99, image: "../assets/The-great-gatsby.jpg", quantity: 2 },
        { id: 3, title: "Design Systems", author: "Alla Kholmatova", price: 45.00, image: "../assets/The-great-gatsby.jpg", quantity: 1 }
    ];
    
    cartItems.forEach(item => {
        booksHTML += `
            <div class="card" data-id="${item.id}">
                <div class="card-image">
                    <img class="book-image" src="${item.image}" alt="${item.title}">
                </div>
                
                <div class="card-content">
                    <div class="card-header">
                        <div>
                            <div class="card-title">${item.title}</div>
                            <div class="card-author">By ${item.author}</div>
                        </div>
                        <button class="delete-btn" onclick="removeItem(${item.id})">
                            <i class="fa-solid fa-trash" style="font-size: 15px"></i>
                        </button>
                    </div>
                    
                    <div class="card-footer">       
                        <div class="quantity-controls">
                            <button class="qty-btn" onclick="updateQuantity(${item.id}, -1)">−</button>
                            <input type="text" class="qty-input" value="${item.quantity}" readonly>
                            <button class="qty-btn add" onclick="updateQuantity(${item.id}, 1)">+</button>
                        </div>
                        <div class="card-price">$ ${item.price.toFixed(2)}</div>
                    </div>
                </div>
            </div>
        `;
    });
    
    return booksHTML;
}


function updateQuantity(itemId, change) {
    const card = document.querySelector(`.card[data-id="${itemId}"]`);
    const input = card.querySelector('.qty-input');
    let currentQty = parseInt(input.value);
    let newQty = currentQty + change;
    
    if (newQty > 0) {
        input.value = newQty;
    }
}

// Add functionality for removing items
function removeItem(itemId) {
    const card = document.querySelector(`.card[data-id="${itemId}"]`);
    card.style.transition = 'opacity 0.3s ease';
    card.style.opacity = '0';
    setTimeout(() => {
        card.remove();
    }, 300);
}


root.innerHTML += createCartPage();