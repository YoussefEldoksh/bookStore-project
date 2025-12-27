<?php
require "../../Backend/bookdb.php";
session_start();

// Redirect to login page if not logged in or not an Admin
if (!isset($_SESSION['user_id']) || $_SESSION['type'] !== 'Admin') {
    header("Location: ../admin-login-view/login.php");
    exit;
}

// Fetch book details from the database using the ISBN
if (isset($_GET['book_isbn'])) {
    $book_isbn = $_GET['book_isbn'];

    // Query to fetch the specific columns we need
    $stmt = $conn->prepare("SELECT book_isbn, title, pub_year, selling_price, category_id, quantity_in_stock FROM book WHERE book_isbn = ?");
    $stmt->bind_param("s", $book_isbn);
    $stmt->execute();
    $stmt->bind_result($book_isbn, $title, $pub_year, $selling_price, $category_id, $quantity_in_stock);  // Bind all the result variables

    if ($stmt->fetch()) {  // Fetch the data into the variables
        // Now the $book variable should be populated
        $book = [
            'book_isbn' => $book_isbn,
            'title' => $title,
            'pub_year' => $pub_year,
            'selling_price' => $selling_price,
            'category_id' => $category_id,
            'quantity_in_stock' => $quantity_in_stock
        ];
    } else {
        echo "No book found with this ISBN.";
        exit;
    }
} else {
    echo "Invalid book ISBN.";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./manageBook-style.css">
    <script type="module" src="./manageBook-script.js" defer></script>
    <title>Admin Manage Books</title>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Admin Panel</h2>
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
        <button class="sidebar-toggle" id="sidebar-toggle">☰</button>
    </div>

    <div class="main-content">
        <div class="form-card">
            <h2 class="right-div-title">Manage Book</h2>

            <!-- Form for managing book -->
            <form method="POST">
                <label for="isbn">ISBN:</label>
                <input type="text" name="book_isbn" value="<?= htmlspecialchars($book['book_isbn']) ?>" readonly required />

                <label for="title">Book Title:</label>
                <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" required />

                <label for="pub_year">Publication Year:</label>
                <input type="number" name="pub_year" value="<?= htmlspecialchars($book['pub_year']) ?>" required />

                <label for="price">Selling Price:</label>
                <input type="number" name="price" value="<?= htmlspecialchars($book['selling_price']) ?>" required />

                <label for="category-select">Choose a Category:</label>
                <select name="category_id" required>
                    <option value="1" <?= $book['category_id'] == 1 ? 'selected' : '' ?>>Science</option>
                    <option value="2" <?= $book['category_id'] == 2 ? 'selected' : '' ?>>Art</option>
                    <option value="3" <?= $book['category_id'] == 3 ? 'selected' : '' ?>>Religion</option>
                    <option value="4" <?= $book['category_id'] == 4 ? 'selected' : '' ?>>History</option>
                    <option value="5" <?= $book['category_id'] == 5 ? 'selected' : '' ?>>Geography</option>
                </select>

                <p>Available Quantity in Stock: <?= htmlspecialchars($book['quantity_in_stock']) ?></p>

                <label for="sold_qty">Enter Sold Quantity (Stock to Reduce):</label>
                <input type="number" name="sold_qty" min="0" required />

                <button type="submit">Update Book</button>
            </form>
        </div>
    </div>
</body>

</html>

