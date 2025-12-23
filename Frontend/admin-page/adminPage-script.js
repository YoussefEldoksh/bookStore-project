document.addEventListener("DOMContentLoaded", () => {
    // Get main content
    const content = document.getElementById("content");

    // Function to load the Main Page cards
    function loadMainPage() {
        content.innerHTML = `
        <div class="greeting-section">
          <h1>Hello ${username}!</h1>
          <p class="subtitle">Welcome to your admin dashboard. Manage your bookstore with ease.</p>
        </div>
        <div class="dashboard-cards">
            <div class="card card_1" data-page="../admin-dashboard/dashboard.php">
                <i class="fa-solid fa-chart-line fa-2x"></i>
                <h3>Dashboard</h3>
                <p>View statistics and overview.</p>
            </div>
            <div class="card card_2" data-page="../add-Book/addBook.php">
                <i class="fa-solid fa-book-medical fa-2x"></i>
                <h3>Add New Book</h3>
                <p>Add books to your store.</p>
            </div>
            <div class="card card_3" data-page="../manage-books/manageBooks.php">
                <i class="fa-solid fa-pen-to-square fa-2x"></i>
                <h3>Modify Books</h3>
                <p>Edit or remove existing books.</p>
            </div>
            <div class="card card_4" data-page="../orders/orders.php">
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

    // Load default section on page load (Home/dashboard)
    loadMainPage();
});


