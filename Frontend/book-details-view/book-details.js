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

function createBookDetailsHTML(book) {
    const bookInfo = book.volumeInfo;
    const saleInfo = book.saleInfo;
    
    // Extract data with fallbacks
    const title = bookInfo.title || 'Unknown Title';
    const authors = bookInfo.authors ? bookInfo.authors.join(', ') : 'Unknown Author';
    const description = bookInfo.description || 'No description available';
    const imageUrl = bookInfo.imageLinks?.thumbnail || 'assets/default-book.jpg';
    const publisher = bookInfo.publisher || 'Unknown Publisher';
    const publishedDate = bookInfo.publishedDate || 'Unknown Date';
    const pageCount = bookInfo.pageCount || 'N/A';
    const categories = bookInfo.categories ? bookInfo.categories.join(', ') : 'N/A';
    const language = bookInfo.language || 'Unknown';
    const price = saleInfo?.listPrice?.amount || 'Price not available';
    const currency = saleInfo?.listPrice?.currencyCode || 'EGP';
    
    return `
        <div class="book-image-container">
            <img src="${imageUrl}" alt="${title}" class="book-image"/>
        </div>
        <div class="book-info-container">
            <div style="margin-top: 2%; display:flex; gap: 1%; justify-content: left; align-items: center">
                <p class="book-title">${title}</p>
                <div style="width: 70%;display: flex;gap: 2%;justify-contetn: end;flex-direction: row;justify-content: flex-end;">
                <div class="price"> 300 EGP </div>
                <button class="add-to-cart-btn">Add to Cart</button>
                <button class="fav"><i class="fa-regular fa-heart"></i></button>
                </div>  
            </div>
            <div style="margin-top: 1%; display: flex;">
                <p class="book-data" style="text-align: left;">Author: ${authors}</p>
            </div>
            <div style="margin-top: 0%; display: flex;">
                <p class="book-data" style="text-align: left;">Genre: ${categories}</p>
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
            <p class="book-description">${description}</p>
        </div>
    `;
}

async function fetchBookDetails(bookId) {
    try {
        const response = await fetch(`https://www.googleapis.com/books/v1/volumes/${bookId}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        
        const bookData = await response.json();
        console.log('Book Details:', bookData);
        
        // Display the book details
        const detailsContainer = document.getElementById('book-details-content');
        if (detailsContainer) {
            detailsContainer.innerHTML = createBookDetailsHTML(bookData);
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
document.addEventListener('DOMContentLoaded', function() {
    root_div.innerHTML += createBookViewStructure();
    
    const bookId = getBookIdFromURL();
    
    if (bookId) {
        fetchBookDetails(bookId);
    } else {
        const detailsContainer = document.getElementById('book-details-content');
        if (detailsContainer) {
            detailsContainer.innerHTML = '<p style="padding: 20px;">No book selected. Please go back and select a book.</p>';
        }
    }
});