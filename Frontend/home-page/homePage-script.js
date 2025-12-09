let root_div = document.getElementById("root");

function createSearchBar() {
    let nav_bar = `
<div class="home-page">
    <div class="input-container">
        <i class="fa-solid fa-magnifying-glass"></i>
          <input
            class="input"
            name="text"
            type="text"
            placeholder="Title, author, host, or topic..."/>
        <br>
        </div>

            <div class="Category-section" >
                 <p class="Category-title">Categories:</p>
                    <div class="categories-list">
                        <button class="category-btn">Science Fiction</button>
                        <button class="category-btn">Romance</button>
                        <button class="category-btn">Mystery</button>
                        <button class="category-btn">Fantasy</button>
                        <button class="category-btn">Non-Fiction</button>
                        <button class="category-btn">Historical</button>
                        <button class="category-btn">Thriller</button>
                        <button class="category-btn">Young Adult</button>
                        <button class="category-btn">Horror</button>
                        <button class="category-btn">Biography</button>
                        <button class="category-btn">Self-Help</button>
                        <button class="category-btn">Graphic Novels</button>
                    
                        
                    </div>
            </div>

            <div class="books-section"> 
                <p class="books-title">Popular Books:</p>
                <div class="books-list">
                        ${printBooks()}
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
                        <img src="assets/The-great-gatsby.jpg" alt="Book ${i}" class="book-image"/>
                        <p class="book-title">The great gatsby</p>
                        <p class="book-author">F. Scott Fitzberg</p>
                        <div class="book-rating" style="display: flex; align-items: center; gap: 20%;">
                            <div class="rating-stars" style="display: flex; gap: 2px;">
                            <i id="rating" class="fa-regular fa-star" style="font-size:11px"></i>
                            <i id="rating" class="fa-regular fa-star" style="font-size:11px"></i>
                            <i id="rating" class="fa-regular fa-star" style="font-size:11px"></i>
                            <i id="rating" class="fa-regular fa-star" style="font-size:11px"></i>   
                            <i id="rating" class="fa-regular fa-star" style="font-size:11px"></i>
                            </div>
                            <div class="rating-value" ><i class="fa-regular fa-bookmark" style="font-size:13px;"></i></div>
                        </div>
                    </div>
                    `;
    }
    return booksHTML;
}
root_div.innerHTML += createSearchBar();
