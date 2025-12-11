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
                 <p class="Category-title">Categories:</p>
                    <div class="categories-list">
                        <a class="category-btn">Science Fiction</a>
                        <a class="category-btn">Romance</a>
                        <a class="category-btn">Mystery</a>
                        <a class="category-btn">Fantasy</a>
                        <a class="category-btn">Non-Fiction</a>
                        <a class="category-btn">Historical</a>
                        <a class="category-btn">Thriller</a>
                        <a class="category-btn">Young Adult</a>
                        <a class="category-btn">Horror</a>
                        <a class="category-btn">Biography</a>
                        <a class="category-btn">Self-Help</a>
                        <a class="category-btn">Graphic Novels</a>
                    
                        
                    </div>
            </div>

            <div class="books-section"> 
                <p class="books-title">Popular Books:</p>
                <div class="books-list">
                        ${printBooks()}
                </div>
            </div>
    </div>
</div>
    `;  
    return nav_bar;
}

function printBooks() {



    let booksHTML = "";
    for (let i = 1; i <= 50; i++) {
        booksHTML += `
                    <div class="book-item">
                        <a href="/book-details-view/book-details.html">
                        <img src="assets/The-great-gatsby.jpg" alt="Book ${i}" class="book-image"/>
                        </a>
                        <p class="book-title">The great gatsby</p>
                        <p class="book-author">F. Scott Fitzberg</p>
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
root_div.innerHTML += createSearchBar();
