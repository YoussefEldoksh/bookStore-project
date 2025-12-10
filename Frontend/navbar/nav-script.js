

let root_div = document.getElementById("root");

function createNavBar() {
    let nav_bar = `
    <nav>
        <ul id="nav-icons">
            <li><a href="./index.html"> <i class="fa-solid fa-house" ></i></a>
            <p>Home</p>
            </li>
            <li><a href="./about.html"><i class="fa-solid fa-layer-group"></i></a><p>Categories</p></li>
            <li><a href="./books.html"><i class="fa-solid fa-book"></i></a><p>Library</p></li>
            <li><a href="./about.html"><i class="fa-solid fa-bookmark"></i></a><p>Saved</p></li>

        </ul> 
        
        <ul id="nav-links">
            <li><a href="./index.html"><i class="fa-solid fa-user"></i></a><p>Account</p></li>
            <li><a href="./contact.html"><i class="fa-solid fa-handshake"></i></a><p>Contact Us</p></li>            <li><a href="./contact.html"><i class="fa-solid fa-door-open"></i></a><p>Logout</p></li>

        </ul>   
    </nav>
    `;
    return nav_bar;
}
    
root_div.innerHTML += createNavBar();