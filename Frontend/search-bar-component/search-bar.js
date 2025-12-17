

let root = document.getElementById("root");

function createSearchBar(){

    let searchbar = `
        <div class="input-container">
                <a href="./index.html"><i class="fa-solid fa-magnifying-glass"></i></a>
                <input
                    class="input"
                    name="text"
                    type="text"
                    placeholder="Title, author, host, or topic..."/>
            </div>
    `;

    return searchbar;
}

root.innerHTML +=  createSearchBar();