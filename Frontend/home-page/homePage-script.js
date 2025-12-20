let root_div = document.getElementById("root");

function createSearchBar() {
  let nav_bar = `
<div class="home-page">
    <div class="input-container">
        <a href = "./index.html"><i class="fa-solid fa-magnifying-glass"></i></a>
          <input
            class="input"
            name="text"
            type="text"
            placeholder="Title, author, host, or topic..."/>

        </div>
    <div class="home-container">
        <div class="home-header">
            <div class="welcome-text">
                <p class="heading-text" >READ</p>
                <p class="heading-text" style="color: #0f117d" >WITH</p>
                <p class="heading-text" >INTENT.</p>
                <div class="statement-container">
                <p class="statement" >A curated collection of fiction and non-fiction for curious minds. From timeless classics to modern thought, this shop exists to connect readers with ideas that last.</p>
                </div>
                <button class="explore-btn"> Browse Library </button>
                <button class="books-btn"> VIEW CATEGORIES → </button>

                </div>

            <div class="home-image-container">
    <div class="badge-container">
        <div class="badge-outer">
            <div class="badge-inner">
                <p class="badge-text-large">100%</p>
                <p class="badge-text-small">SATISFACTION GUARANTEED</p>
            </div>
        </div>
        <div class="star-badge">
            <div class="star">★</div>
        </div>
    </div>
            </div>
        </div>

            <div class="Category-section" >
                    <div class="categories-list" id="categories-list">
                        <a  class="category-btn" data-genre="Science Fiction">Science Fiction</a>
                        <a  class="category-btn" data-genre="Romance">Romance</a>
                        <a  class="category-btn" data-genre="Mystery">Mystery</a>
                        <a  class="category-btn" data-genre="Fantasy">Fantasy</a>
                        <a  class="category-btn" data-genre="Non-Fiction">Non-Fiction</a>
                        <a  class="category-btn" data-genre="Historical">Historical</a>
                        <a  class="category-btn" data-genre="Thriller">Thriller</a>
                        <a  class="category-btn" data-genre="Young Adult">Young Adult</a>
                        <a  class="category-btn" data-genre="Horror">Horror</a>
                        <a  class="category-btn" data-genre="Biography">Biography</a>
                        <a  class="category-btn" data-genre="Self-Help">Self-Help</a>
                        <a  class="category-btn" data-genre="Science">Science</a>
                        <a  class="category-btn" data-genre="Art">Art</a>
                        <a  class="category-btn" data-genre="Religion">Religion</a>
                        <a  class="category-btn" data-genre="History">History</a>
                        <a  class="category-btn" data-genre="Geography">Geography</a>
                    </div>
            </div>

            <div class="books-section"> 
                <p class="books-title">Popular Books:</p>
                <div class="books-list" id="books-list">
                        <!-- Books will be loaded here -->
                </div>
            </div>
    </div>
</div>
    `;
  return nav_bar;
}

async function fetchBookImg(bookName){
    try {
      const response = await fetch(
      `https://www.googleapis.com/books/v1/volumes?q=${bookName}`
    );
    if(!response.ok){
        throw new Error(`HTTP error couldn't resolve book image! Status: ${response.status}`);
    }
    const data = await response.json();
    const book = data.items[0];
    const bookInfo = book.volumeInfo;
    const imageUrl = bookInfo.imageLinks?.thumbnail;
    return imageUrl;
    } catch (error) {
          console.error("Fetch error:", error.message);
          return null; // Return null if image fetch fails
    }
}

function getLoaderHTML() {
  document.querySelector(".books-list").style.justifyContent = "center";
  return `<div class="loader">
  <div class="truckWrapper">
    <div class="truckBody">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 198 93"
        class="trucksvg"
      >
        <path
          stroke-width="3"
          stroke="#282828"
          fill="#F83D3D"
          d="M135 22.5H177.264C178.295 22.5 179.22 23.133 179.594 24.0939L192.33 56.8443C192.442 57.1332 192.5 57.4404 192.5 57.7504V89C192.5 90.3807 191.381 91.5 190 91.5H135C133.619 91.5 132.5 90.3807 132.5 89V25C132.5 23.6193 133.619 22.5 135 22.5Z"
        ></path>
        <path
          stroke-width="3"
          stroke="#282828"
          fill="#7D7C7C"
          d="M146 33.5H181.741C182.779 33.5 183.709 34.1415 184.078 35.112L190.538 52.112C191.16 53.748 189.951 55.5 188.201 55.5H146C144.619 55.5 143.5 54.3807 143.5 53V36C143.5 34.6193 144.619 33.5 146 33.5Z"
        ></path>
        <path
          stroke-width="2"
          stroke="#282828"
          fill="#282828"
          d="M150 65C150 65.39 149.763 65.8656 149.127 66.2893C148.499 66.7083 147.573 67 146.5 67C145.427 67 144.501 66.7083 143.873 66.2893C143.237 65.8656 143 65.39 143 65C143 64.61 143.237 64.1344 143.873 63.7107C144.501 63.2917 145.427 63 146.5 63C147.573 63 148.499 63.2917 149.127 63.7107C149.763 64.1344 150 64.61 150 65Z"
        ></path>
        <rect
          stroke-width="2"
          stroke="#282828"
          fill="#FFFCAB"
          rx="1"
          height="7"
          width="5"
          y="63"
          x="187"
        ></rect>
        <rect
          stroke-width="2"
          stroke="#282828"
          fill="#282828"
          rx="1"
          height="11"
          width="4"
          y="81"
          x="193"
        ></rect>
        <rect
          stroke-width="3"
          stroke="#282828"
          fill="#DFDFDF"
          rx="2.5"
          height="90"
          width="121"
          y="1.5"
          x="6.5"
        ></rect>
        <rect
          stroke-width="2"
          stroke="#282828"
          fill="#DFDFDF"
          rx="2"
          height="4"
          width="6"
          y="84"
          x="1"
        ></rect>
      </svg>
    </div>
    <div class="truckTires">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 30 30"
        class="tiresvg"
      >
        <circle
          stroke-width="3"
          stroke="#282828"
          fill="#282828"
          r="13.5"
          cy="15"
          cx="15"
        ></circle>
        <circle fill="#DFDFDF" r="7" cy="15" cx="15"></circle>
      </svg>
      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 30 30"
        class="tiresvg"
      >
        <circle
          stroke-width="3"
          stroke="#282828"
          fill="#282828"
          r="13.5"
          cy="15"
          cx="15"
        ></circle>
        <circle fill="#DFDFDF" r="7" cy="15" cx="15"></circle>
      </svg>
    </div>
    <div class="road"></div>

    <svg
      xml:space="preserve"
      viewBox="0 0 453.459 453.459"
      xmlns:xlink="http://www.w3.org/1999/xlink"
      xmlns="http://www.w3.org/2000/svg"
      id="Capa_1"
      version="1.1"
      fill="#000000"
      class="lampPost"
    >
      <path
        d="M252.882,0c-37.781,0-68.686,29.953-70.245,67.358h-6.917v8.954c-26.109,2.163-45.463,10.011-45.463,19.366h9.993
c-1.65,5.146-2.507,10.54-2.507,16.017c0,28.956,23.558,52.514,52.514,52.514c28.956,0,52.514-23.558,52.514-52.514
c0-5.478-0.856-10.872-2.506-16.017h9.992c0-9.354-19.352-17.204-45.463-19.366v-8.954h-6.149C200.189,38.779,223.924,16,252.882,16
c29.952,0,54.32,24.368,54.32,54.32c0,28.774-11.078,37.009-25.105,47.437c-17.444,12.968-37.216,27.667-37.216,78.884v113.914
h-0.797c-5.068,0-9.174,4.108-9.174,9.177c0,2.844,1.293,5.383,3.321,7.066c-3.432,27.933-26.851,95.744-8.226,115.459v11.202h45.75
v-11.202c18.625-19.715-4.794-87.527-8.227-115.459c2.029-1.683,3.322-4.223,3.322-7.066c0-5.068-4.107-9.177-9.176-9.177h-0.795
V196.641c0-43.174,14.942-54.283,30.762-66.043c14.793-10.997,31.559-23.461,31.559-60.277C323.202,31.545,291.656,0,252.882,0z
M232.77,111.694c0,23.442-19.071,42.514-42.514,42.514c-23.442,0-42.514-19.072-42.514-42.514c0-5.531,1.078-10.957,3.141-16.017
h78.747C231.693,100.736,232.77,106.162,232.77,111.694z"
      ></path>
    </svg>
  </div>
</div>
`;
}

async function printBooks(books = null) {
  let booksHTML = "";

  // If no books provided, show default/empty state
  if (!books) {
    return `
        <div class="fall-back-container">
          <div class="fall-back-img-container">
            <img class="fall-back-img" src="../assets/peep-2.png" alt="">
            <div class="error-container">
                  <h1> 404</h1>
                  <p style="font-size: 20px; text-align: left"> This category is not available currently</p>
            </div>    
            </div>
            <div>

            </div>
        </div>
    `;
  }

  for (let i = 0; i < books.length; i++) {
    const book = books[i];
    const author = book.author || "Unknown Author";
    const bookId = book.book_isbn;
    const price = book.selling_price || 300; 
    const bookTitle = book.title
    const imageUrl = await fetchBookImg(bookTitle);
    booksHTML += `
                    <div class="book-item" data-book-id="${bookId}">
                        <a href="../book-details-view/book-details.html?bookId=${bookId}">
                        <img src="${imageUrl}" alt="${bookTitle}" class="book-image"/>
                        </a>
                        <p class="book-title">${bookTitle}</p>
                        <p class="book-author">${author}</p>
                        <div class="book-rating" style="display: flex; align-items: center; gap: 2%;">
                            <div class="rating-stars" style="display: flex; gap: 2px;">
                            <i id="rating" class="fa-solid fa-star" style="font-size:11px"></i>
                            <i id="rating" class="fa-solid fa-star" style="font-size:11px"></i>
                            <i id="rating" class="fa-solid fa-star" style="font-size:11px"></i>
                            <i id="rating" class="fa-regular fa-star" style="font-size:11px"></i>   
                            <i id="rating" class="fa-regular fa-star" style="font-size:11px"></i>
                            </div>
                            <div class="book-price" style="hight:fit-content">
                                 <p style="font-size:18px">${price} EGP</p> 
                            </div>
                            <div class="cart-btns">
                            <button class="add-item-btn">ADD TO CART</button>
                            <button class="add-to-fav"><i class="fa-solid fa-bookmark" style="font-size:10px; color: #000"></i></button>
                            </div>
                        </div>
                    </div>
                    `;
  }
  document.querySelector(".books-list").style.justifyContent = "left"
  return booksHTML;
}

function colorEachCategory() {
  const buttons = document.querySelectorAll(".category-btn");
  const cards = document.querySelectorAll(".book-item");
  const colors = [
    "#dbeda0ff",
    "rgba(157, 255, 157, 1)",
    "#D9CCEB",
    "rgba(251, 245, 134, 1)",
    "rgba(238, 185, 238, 1)",
    "rgba(179, 246, 246, 1)",
  ];

  buttons.forEach((button, index) => {
    button.style.backgroundColor = colors[index % colors.length];
  });

  cards.forEach((card, index) => {
    card.style.backgroundColor = colors[index % colors.length];
  });
}

function colorEachCardButton() {
  const addBtn = document.querySelectorAll(".add-item-btn");
  const favBtn = document.querySelectorAll(".add-to-fav");
  const colors = [
    "rgb(107, 255, 107)", // Bright green
    "rgb(255, 255, 107)", // Bright yellow
    "rgb(107, 107, 255)", // Bright blue
    "rgb(255, 107, 107)", // Bright red
    "rgb(255, 107, 255)", // Bright magenta
    "rgb(107, 255, 255)", // Bright cyan
    "rgb(255, 165, 0)", // Bright orange
    "rgb(255, 20, 147)", // Deep pink
    "rgb(30, 144, 255)", // Dodger blue
    "rgb(138, 43, 226)",
  ];

  addBtn.forEach((Btn, index) => {
    Btn.style.backgroundColor = colors[index % colors.length];
  });

  favBtn.forEach((Btn, index) => {
    Btn.style.backgroundColor = colors[(index + 1) % colors.length];
  });
}

const addToFavButtons = document.querySelectorAll(".add-to-fav");

document.addEventListener("click", (e) => {
  if (e.target.classList.contains("add-to-fav")) {
    e.preventDefault();

    const token = localStorage.getItem("authToken");

    if (!token) {
      const currentPage = window.location.pathname;
      window.location.href = `../login-view/login.html?redirect=${encodeURIComponent(
        currentPage
      )}`;

      return;
    }

    const bookCard = e.target.closest(".book-item");
    addToFav(bookCard);
  }
});

const addToCartButtons = document.querySelectorAll(".add-item-btn");
document.addEventListener("click", (e) => {
  if (e.target.classList.contains("add-item-btn")) {
    e.preventDefault();

    const token = localStorage.getItem("authToken");

    if (!token) {
      const currentPage = window.location.pathname;
      window.location.href = `../login-view/login.html?redirect=${encodeURIComponent(
        currentPage
      )}`;
      return;
    }

    // Get the book card and extract data you need
    const bookCard = e.target.closest(".book-item");

    const bookId = bookCard.dataset.bookId;
    const bookTitle = bookCard.querySelector(".book-title").textContent;
    const bookAuthor = bookCard.querySelector(".book-author").textContent;
    const bookImage = bookCard.querySelector(".book-image").src;
    const price = bookCard.querySelector(".book-price").textContent.trim()
    const bookObj = {
      bookId: bookId,
      bookTitle: bookTitle,
      bookAuthor: bookAuthor,
      bookImage: bookImage,
      price: price,
      quantity: 1
    }


    let cartArray = localStorage.getItem("cartArray");

    if (!cartArray) {
      cartArray = [bookObj];
    } else {
      cartArray = JSON.parse(cartArray);
      const existingItem = cartArray.find(item => item.bookId === bookId);

      if(!existingItem){
        cartArray.push(bookObj);
      }
    }
    
    localStorage.setItem("cartArray", JSON.stringify(cartArray));
    alert("Item added to cart!");

  }
});

async function fetchData(genre) {
  try {
    // Show loader immediately
    const booksList = document.getElementById("books-list");
    booksList.innerHTML = getLoaderHTML();

    const response = await fetch('getbook.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'submit=1&category=' + encodeURIComponent(genre)
    });

    // Get the raw text first
    const text = await response.text();
    console.log('Raw response:', text);
    console.log('Response length:', text.length);

    // Try to parse it
    const data = JSON.parse(text);
    
    // Update the books list with the fetched data
    if (booksList) {
    if (data.data && data.data.length > 0) {
      booksList.innerHTML = await printBooks(data.data);
    } else {
      booksList.innerHTML = await printBooks(null);
    }      colorEachCategory();
      colorEachCardButton();
    }

    return data;
  } catch (error) {
    console.error("Fetch error:", error.message);
    const booksList = document.getElementById("books-list");
    if (booksList) {
      booksList.innerHTML =
        '<p style="padding: 20px; text-align: center;">Error loading books. Please try again.</p>';
    }
  }
}

// Initialize the page
root_div.innerHTML += createSearchBar();

// Set up event delegation for category buttons
document.addEventListener("DOMContentLoaded", function () {
  const categoriesList = document.getElementById("categories-list");

  if (categoriesList) {
    categoriesList.addEventListener("click", function (e) {
      // Check if clicked element is a category button
      if (e.target.classList.contains("category-btn")) {
        const genre = e.target.getAttribute("data-genre");
        if (genre) {
          fetchData(genre);
        }
      }
    });
  }

  // Load default fantasy books on page load
  fetchData("");

  // Color the category buttons
  colorEachCategory();
  colorEachCardButton();
});