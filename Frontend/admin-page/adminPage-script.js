// Get sidebar links and main content
const content = document.getElementById("content");
const sidebarLinks = document.querySelectorAll(".menu a");

// Function to load the Main Page cards
function loadMainPage() {
    content.innerHTML = `
        <div class="dashboard-cards">
            <div class="card" data-page="../admin-dashboard/dashboard.php">
                <i class="fa-solid fa-chart-line fa-2x"></i>
                <h3>Dashboard</h3>
                <p>View statistics and overview.</p>
            </div>
            <div class="card" data-page="../add-Book/addBook.php">
                <i class="fa-solid fa-book-medical fa-2x"></i>
                <h3>Add New Book</h3>
                <p>Add books to your store.</p>
            </div>
            <div class="card" data-page="../manage-books/manageBooks.php">
                <i class="fa-solid fa-pen-to-square fa-2x"></i>
                <h3>Modify Books</h3>
                <p>Edit or remove existing books.</p>
            </div>
            <div class="card" data-page="../orders/orders.php">
                <i class="fa-solid fa-box fa-2x"></i>
                <h3>Orders</h3>
                <p>Check and manage orders.</p>
            </div>
        </div>
    `;
    attachCardClickEvents();
}

// Attach click events to all cards
function attachCardClickEvents() {
    const cards = document.querySelectorAll(".card");
    cards.forEach(card => {
        card.addEventListener("click", () => {
            const page = card.dataset.page;
            if (page) {
                window.location.href = page; // redirect to the page
            }
        });
    });
}

// Handle sidebar navigation
sidebarLinks.forEach(link => {
    link.addEventListener("click", e => {
        e.preventDefault();

        // Remove active class from all links
        sidebarLinks.forEach(l => l.classList.remove("active"));
        // Add active class to clicked link
        link.classList.add("active");

        const section = link.dataset.section.toLowerCase();

        switch (section) {
            case "home":
                loadMainPage();
                break;
            case "profile":
                window.location.href = "../admin-profile.php"; // replace with actual profile page
                break;
            case "search":
                window.location.href = "../search.php"; // replace with actual search page
                break;
            case "logout":
                window.location.href = "../logout.php"; // logout page
                break;
        }
    });
});

// Load default section on page load (Home/dashboard)
document.addEventListener("DOMContentLoaded", () => {
    loadMainPage();
});


