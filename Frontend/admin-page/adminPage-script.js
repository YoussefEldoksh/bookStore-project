// adminPage-script.js

const content = document.getElementById("content");
const links = document.querySelectorAll(".menu a");

// Function to load sections dynamically
export function loadSection(section) {
    switch(section) {
        case "home":
            content.innerHTML = `
                <h1>Admin Dashboard</h1>
                <p>Welcome to the admin panel.</p>
                <div class="dashboard-cards">
                    <div class="card" data-section="dashboard">
                        <i class="fa-solid fa-chart-line fa-2x"></i>
                        <h3>Dashboard</h3>
                        <p>View statistics and overview.</p>
                    </div>
                    <div class="card" data-section="add-book">
                        <i class="fa-solid fa-book-medical fa-2x"></i>
                        <h3>Add New Book</h3>
                        <p>Add books to your store.</p>
                    </div>
                    <div class="card" data-section="manage-books">
                        <i class="fa-solid fa-pen-to-square fa-2x"></i>
                        <h3>Modify Books</h3>
                        <p>Edit or remove existing books.</p>
                    </div>
                    <div class="card" data-section="orders">
                        <i class="fa-solid fa-box fa-2x"></i>
                        <h3>Orders</h3>
                        <p>Check and manage orders.</p>
                    </div>
                </div>
            `;
            attachCardClickEvents(); // make cards clickable
            break;

        case "profile":
            content.innerHTML = "<h1>Profile</h1><p>Manage your profile here.</p>";
            break;

        case "search":
            content.innerHTML = "<h1>Search</h1><p>Search for books here.</p>";
            break;

        case "logout":
            // Trigger logout
            window.location.href = "../logout.php";
            break;
    }
}

// Add click events to sidebar links
links.forEach(link => {
    link.addEventListener("click", e => {
        e.preventDefault();
        links.forEach(l => l.classList.remove("active"));
        link.classList.add("active");
        const section = link.dataset.section.toLowerCase();
        loadSection(section);
    });
});

// Add click events to cards
function attachCardClickEvents() {
    const cards = document.querySelectorAll(".card");
    cards.forEach(card => {
        card.addEventListener("click", () => {
            const section = card.dataset.section.toLowerCase();
            if (section === "add-book") {
                window.location.href = "../add-book/addBook.php"; // redirect to the PHP page
                return;
            }
            links.forEach(l => l.classList.remove("active"));
            const sidebarLink = document.querySelector(`.menu a[data-section='${section}']`);
            if(sidebarLink) sidebarLink.classList.add("active");
            loadSection(section);
        });
    });
}

// Load default section on page load
document.addEventListener("DOMContentLoaded", () => {
    const defaultLink = document.querySelector(".menu a.active");
    const defaultSection = defaultLink.dataset.section.toLowerCase();
    loadSection(defaultSection);
});
