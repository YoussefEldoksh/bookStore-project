

let root_div = document.getElementById("root");

function createBookView() {
    let nav_bar = `
<div class="book-details-page">
    <div class="input-container">
        <a href = "./index.html"><i class="fa-solid fa-magnifying-glass"></i></a>
          <input
            class="input"
            name="text"
            type="text"
            placeholder="Title, author, host, or topic..."/>

        </div>
    <div class="book-details-header">
        <a href = "../index.html"> <i class="fa-solid fa-arrow-left"> </i> <span class="back-text" >Back to results</span> </a>
    </div>      
    <div class="book-details-section">
        <div class="book-image-container">
            <img src="../assets/The-great-gatsby.jpg" alt="Book Image" class="book-image"/>
        </div>
        <div class="book-info-container">
        <div style="margin-top: 3%; display:flex; gap: 2%" > <p class="book-title">The Great Gatsby</p><div class="price"> 300 EGP</div>  <button> Add to Cart</button> <button class="fav"> <i class="fa-regular fa-heart"></i></button></div>
         <div style="margin-top: 0%;display: flex;"> <p class="book-data" style="text-align: left; ">Author: F. Scott Fitzgerald </p> </div> 
         <div style="margin-top: 0%;display: flex;"> <p class="book-data" style="text-align: left; ">Genre: Classic Fiction, Literary Fiction </p>  </div> 
         <div style="margin-top: 0%;display: flex;"> <p class="book-data" style="text-align: left; ">Published: 1925</p> </div> 
         <div style="margin-top: 0%;display: flex;"> <p class="book-data" style="text-align: left; ">Language: English</p> </div> 
         <div style="margin-top: 0%;display: flex;"> <p class="book-data" style="text-align: left; ">Pages: 180</p> </div> 
         <div style="margin-top: 0%;display: flex;"> <p class="book-data" style="text-align: left; ">ISBN: 978-0743273565</p> </div> 
         <div style="margin-top: 0%;display: flex;"> <p class="book-data" style="text-align: left; ">Publisher: Charles Scribner’s Sons</p> </div> 
         
         <p class="book-description">"The Great Gatsby" by F. Scott Fitzgerald is a novel written in the early 20th century. The story is mainly narrated by Nick Carraway, who reflects on the life of his enigmatic neighbor, Jay Gatsby, and the extravagant world of wealth and excess he inhabits. The novel explores themes of the American Dream, love, and social class. At the start of the novel, Nick Carraway reflects on advice from his father about withholding judgment of others, which sets the stage for the unfolding narrative. We learn about Nick's background, his move to West Egg, and his connection to wealthy acquaintances like Tom and Daisy Buchanan. Nick's first glimpse of Gatsby is during a moment of solitude when he sees Gatsby reaching out toward a distant green light, symbolizing his unattainable dreams. This opening portion lays the groundwork for the intricate relationships and social dynamics in the world of 1920s America, hinting at the luxurious yet hollow lives that many characters lead.</p>
        </div>



</div>

    `;
    return nav_bar;
}


root_div.innerHTML += createBookView();