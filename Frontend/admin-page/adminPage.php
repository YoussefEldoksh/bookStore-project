<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['type'] !== 'Admin') {
    header("Location: ../admin-login-view/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./adminPage-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script type="module" src="./adminPage-script.js" defer></script>
    <script>
        const username = "<?php echo $_SESSION['username']; ?>";
    </script>
    <title>Admin Page</title>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Admin Panel</h2> <!-- Optional: Add a title -->
        </div>
        <ul class="sidebar-menu">
            <li><a href="./adminPage.php" id="home-link">Home</a></li>
            <li><a href="../admin-profile/adminProfile.php" id="profile-link">Profile</a></li>
            <li><a href="../admin-search/adminSearch.php" id="search-link">Search</a></li>
            <li>
                <a href="#" id="logout-link">
                    <i class="fa fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
        <!-- Optional: Toggle button for mobile -->
        <button class="sidebar-toggle" id="sidebar-toggle">☰</button>
    </div>

    <!-- MAIN CONTENT -->
    <main class="content" id="content" data-username="<?php echo htmlspecialchars($_SESSION['username']); ?>">
        <div class="greeting-section">
            <div class="text-container">
                <h1>Hello <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                <p class="subtitle">Welcome to your admin page. Manage your bookstore with ease.</p>
            </div>
            <img src="../assets/adminbook.jpg" alt="Books Image" class="books-image">
        </div>
        <div class="dashboard-cards">
            <a href="../admin-dashboard/dashboard.php" class="card-link">
                <div class="card card_1" data-section="dashboard"> <i class="fa-solid fa-chart-bar fa-2x"></i>
                    <h3>Dashboard</h3>
                    <p>View statistics and overview.</p>
                </div>
            </a>
            <a href="../add-book/addBook.php" class="card-link">
                <div class="card card_2" data-section="add-book"> <i class="fa-solid fa-square-plus fa-2x"></i>
                    <h3>Add New Book</h3>
                    <p>Add books to your store.</p>
                </div>
            </a>
            <a href="../admin-manage-books/manageBooks.php" class="card-link">
                <div class="card card_3" data-section="manage-books"> <i class="fa-solid fa-pen-to-square fa-2x"></i>
                    <h3>Modify Books</h3>
                    <p>Edit or remove existing books.</p>
                </div>
            </a>
            <a href="../admin-search/search.php" class="card-link">
                <div class="card card_4" data-section="orders"> <i class="fa-solid fa-dolly fa-2x"></i>
                    <h3>Orders</h3>
                    <p>Check and manage orders.</p>
                </div>
            </a>
        </div>
    </main>

    <div class="logout-modal" id="logout-modal">
        <div class="logout-box">
            <p>Are you sure you want to log out?</p>
            <div class="logout-actions">
                <button id="logout-yes">Yes</button>
                <button id="logout-no">No</button>
            </div>
        </div>
    </div>
</body>
</html>