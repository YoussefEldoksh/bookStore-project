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
                        <a  class="category-btn" data-genre="Graphic Novels">Graphic Novels</a>
                    </div>
            </div>

            <div class="books-section"> 
                <p class="books-title">Popular Books:</p>
                <div class="books-list" id="books-list">
                        ${printBooks()}
                </div>
            </div>
    </div>
</div>
    `;
  return nav_bar;
}

function printBooks(books = null) {
  let booksHTML = "";

  // If no books provided, show default/empty state
  if (!books || !books.items || books.items.length === 0) {
    return `
        <div class="fall-back-container">
          <div class="fall-back-img-container">
            <img class="fall-back-img" src="../assets/peep-2.png" alt="">
            <div class="error-container">
                  <h1> 404</h1>
                  <p style="font-size: 20px; text-align: left"> This category is nott available currently</p>
            </div>    
            </div>
            <div >

            </div>
        </div>
    `
    
    ;
  }

  for (let i = 0; i < Math.min(15, books.items.length); i++) {
    const book = books.items[i];
    const bookInfo = book.volumeInfo;
    const author = bookInfo.authors ? bookInfo.authors[0] : "Unknown Author";
    const imageUrl =
      bookInfo.imageLinks?.thumbnail || "assets/The-great-gatsby.jpg";
    const bookId = book.id;

    booksHTML += `
                    <div class="book-item">
                        <a href="/book-details-view/book-details.html?bookId=${bookId}">
                        <img src="${imageUrl}" alt="${bookInfo.title}" class="book-image"/>
                        </a>
                        <p class="book-title">${bookInfo.title}</p>
                        <p class="book-author">${author}</p>
                        <div class="book-rating" style="display: flex; align-items: center; gap: 20%;">
                            <div class="rating-stars" style="display: flex; gap: 2px;">
                            <i id="rating" class="fa-solid fa-star" style="font-size:11px"></i>
                            <i id="rating" class="fa-solid fa-star" style="font-size:11px"></i>
                            <i id="rating" class="fa-solid fa-star" style="font-size:11px"></i>
                            <i id="rating" class="fa-regular fa-star" style="font-size:11px"></i>   
                            <i id="rating" class="fa-regular fa-star" style="font-size:11px"></i>
                            </div>
                            <div class="cart-btns">
                            <button class="add-item-btn">ADD TO CART</button>
                            <button class="add-to-fav"><i class="fa-solid fa-bookmark" style="font-size:10px; color: #000"></i></button>
                            </div>
                        </div>
                    </div>
                    `;
  }
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
  
  favBtn.forEach((Btn,index)=>{
        Btn.style.backgroundColor = colors[(index + 1) % colors.length]

  })
}
async function fetchData(genre) {
  try {
    const response = await fetch(
      `https://www.googleapis.com/books/v1/volumes?q=subject:${genre.toLowerCase()}&maxResults=15`
    );

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const data = await response.json();
    console.log("Fetched data:", data);

    // Update the books list with the fetched data
    const booksList = document.getElementById("books-list");
    if (booksList) {
      booksList.innerHTML = printBooks(data);
      // Color the cards after they're rendered
      colorEachCategory();
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
  fetchData("Young Adult");

  // Color the category buttons
  colorEachCategory();
  colorEachCardButton();
});
