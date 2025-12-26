<?php
// Start the session
session_start();
require "../../Backend/bookdb.php";

// Redirect to login page if not logged in or not an Admin
if (!isset($_SESSION['user_id']) || $_SESSION['type'] !== 'Admin') {
    header("Location: ../admin-login-view/login.php");
    exit;
}

// If search is requested
$searchQuery = '';
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $stmt = $conn->prepare("
        SELECT * FROM books
        WHERE isbn LIKE ? OR title LIKE ? OR publisher LIKE ? OR author LIKE ?
    ");
    $searchTerm = "%$searchQuery%";
    $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./adminSearch-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script type="module" src="./adminSearch-script.js" defer></script>
    <title>Admin Search</title>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Admin Panel</h2> <!-- Optional: Add a title -->
        </div>
        <ul class="sidebar-menu">
            <li><a href="../admin-page/adminPage.php" id="home-link">Home</a></li>
            <li><a href="../admin-profile/adminProfile.php" id="profile-link">Profile</a></li>
            <li><a href="./adminSearch.php" id="search-link">Search</a></li>
            <li>
                <a href="#" id="logout-link">
                    <i class="fa fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
        <!-- Optional: Toggle button for mobile -->
        <button class="sidebar-toggle" id="sidebar-toggle">☰</button>
    </div>

        <div class="main-content">
        <div class="form-card">
            <h2 class="right-div-title">Search Books</h2>

            <form method="get" class="search-form">
                <label for="search">Search for a book:</label>
                <input type="text" id="search" name="search" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Search by ISBN, name, author, or publisher">
                <button type="submit" class="continue-btn">Search</button>
            </form>

            <div class="books-container">
                <?php
                if (isset($result) && $result->num_rows > 0) {
                    while ($book = $result->fetch_assoc()) {
                        echo "<div class='book-card'>";
                        echo "<h3>" . htmlspecialchars($book['title']) . "</h3>";
                        echo "<p>ISBN: " . htmlspecialchars($book['isbn']) . "</p>";
                        echo "<p>Author: " . htmlspecialchars($book['author']) . "</p>";
                        echo "<p>Publisher: " . htmlspecialchars($book['publisher']) . "</p>";
                        echo "<div class='book-actions'>";
                        echo "<button class='modify-btn' onclick='modifyBook(" . $book['book_id'] . ")'>Modify</button>";
                        echo "<button class='remove-btn' onclick='removeBook(" . $book['book_id'] . ")'>Remove</button>";
                        echo "</div></div>";
                    }
                } else {
                    echo "<p>No books found.</p>";
                }
                ?>
            </div>
        </div>
    </div>
    
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