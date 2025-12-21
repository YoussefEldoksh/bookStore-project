let root_div = document.getElementById("root");

function getBookIdFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('bookId');
}

function createBookViewStructure() {
    let nav_bar = `
<div class="book-details-page">
    <div class="input-container">
        <a href="./index.html"><i class="fa-solid fa-magnifying-glass"></i></a>
        <input
            class="input"
            name="text"
            type="text"
            placeholder="Title, author, host, or topic..."/>
    </div>
    <div class="book-details-header">
        <a href="../home-page/index.html"> <i class="fa-solid fa-arrow-left" id="back-arrow"></i> </a>
    </div>      
    <div class="book-details-section" id="book-details-content">
        <!-- Book details will be loaded here -->
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
      
      // FIX: Check if items exist
      if (!data.items || data.items.length === 0) {
        throw new Error("No books found for: " + bookName);
      }
      
      const book = data.items[0];
      const bookInfo = book.volumeInfo;
      
      // FIX: Check if imageLinks exist before accessing
      const imageUrl = bookInfo.imageLinks?.thumbnail || bookInfo.imageLinks?.smallThumbnail || null;

      const dataObj = {
          imageUrl: imageUrl,
          description: bookInfo.description || 'No description available',
          pageCount: bookInfo.pageCount || 'N/A',
          title: bookInfo.title || 'No title'
      };
      
      console.log("Book data:", dataObj); // Debug log
      return dataObj;
      
    } catch (error) {
          console.error("Fetch error:", error.message);
          return null;
    }
}

async function createBookDetailsHTML(book) {

    const extraInfo = await fetchBookImg(book.title);

    const title = book.title || 'Unknown Title';
    const authors = book.author ? book.author : 'Unknown Author';
    const description = book.description || extraInfo?.description || 'No description available';
    const imageUrl = book.imageUrl || extraInfo?.imageUrl || 'assets/default-book.jpg';
    const publisher = book.publisher || 'Unknown Publisher';
    const publishedDate = book.pub_year || 'Unknown Date';
    const pageCount = book.page_count || extraInfo?.pageCount || 'N/A';
    const categories = book.category_name ? book.category_name : 'N/A';
    const language = book.language || 'Unknown';
    const price = book.selling_price || 'Price not available';
    const currency = 'EGP';


    return `
        <div class="book-image-container">
            <img src="${imageUrl}" alt="${title}" class="book-image"/>
        </div>
        <div class="book-info-container" data-book-id="${book.book_isbn}">
            <div style="margin-top: 2%; display:flex; gap: 1%; justify-content: left; align-items: center">
                <p class="book-title">${title}</p>
                <div style="width: 70%;display: flex;gap: 2%;justify-contetn: end;flex-direction: row;justify-content: flex-end;">
                <div class="price">${price} ${currency}</div>
                <button class="add-to-cart-btn" data-book-id="${book.book_id}">Add to Cart</button>
                <button class="fav"><i class="fa-regular fa-heart"></i></button>
                </div>  
            </div>
            <div style="margin-top: 1%; display: flex;">
                <p class="book-data" id="book-author" style="text-align: left;">Author: ${authors}</p>
            </div>
            <div style="margin-top: 0%; display: flex;">
                <p class="book-data" id="book-category" style="text-align: left;">Genre: ${categories}</p>
            </div>
            <div style="margin-top: 0%; display: flex;">
                <p class="book-data" style="text-align: left;">Published: ${publishedDate}</p>
            </div>
            <div style="margin-top: 0%; display: flex;">
                <p class="book-data" style="text-align: left;">Language: ${language}</p>
            </div>
            <div style="margin-top: 0%; display: flex;">
                <p class="book-data" style="text-align: left;">Pages: ${pageCount}</p>
            </div>
            <div style="margin-top: 0%; display: flex;">
                <p class="book-data" style="text-align: left;">Publisher: ${publisher}</p>
            </div>
            <p class="book-description" style="text-align: left;">${description}</p>
        </div>
    `;
}




const addToCartButtons = document.querySelectorAll('.add-to-cart-btn','.fav');
document.addEventListener('click', (e) => {
  if (e.target.classList.contains('add-to-cart-btn') || e.target.classList.contains('fav')) {
    e.preventDefault();
    
    const token = localStorage.getItem('authToken');
    
    if (!token) {
      const currentPage = window.location.pathname;
      window.location.href = `../login-view/login.html?redirect=${encodeURIComponent(currentPage)}`;
      return;
    }
    
    // Get the book card and extract data you need
    const bookCard = e.target.closest('.book-info-container');
    const bookId = bookCard.dataset.bookId;
    const bookTitle = bookCard.querySelector(".book-title").textContent;
    const bookAuthor = bookCard.querySelector("#book-author").textContent;
    const bookImage = document.querySelector(".book-image").src;
    const price = bookCard.querySelector(".price").textContent.trim()
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

async function fetchBookDetails(bookId) {
    try {
        const response = await fetch("getbookdetails.php",{
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `bookID=${encodeURIComponent(bookId)}&search=1`,
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        
        const bookData = await response.json();
        console.log('Book Details:', bookData);

        
        // Display the book details
        const detailsContainer = document.getElementById('book-details-content');
        if (detailsContainer) {
            detailsContainer.innerHTML = await createBookDetailsHTML(bookData.data);
        }
        
        return bookData;
    } catch (error) {
        console.error('Error fetching book details:', error.message);
        const detailsContainer = document.getElementById('book-details-content');
        if (detailsContainer) {
            detailsContainer.innerHTML = '<p style="padding: 20px;">Error loading book details. Please try again.</p>';
        }
    }
}

// Initialize page
document.addEventListener('DOMContentLoaded', async function() {
    root_div.innerHTML += createBookViewStructure();
    
    const bookId = getBookIdFromURL();
    
    if (bookId) {
        await fetchBookDetails(bookId);
    } else {
        const detailsContainer = document.getElementById('book-details-content');
        if (detailsContainer) {
            detailsContainer.innerHTML = '<p style="padding: 20px;">No book selected. Please go back and select a book.</p>';
        }
    }

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