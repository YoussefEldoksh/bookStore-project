<?php
session_start();

if (!isset($_SESSION["type"]) || $_SESSION["type"] !== "Admin") {
    header("Location: ../Frontend/admin-login-view/login.php");
    exit;
}

require "../../Backend/bookdb.php";

/* Fetch publishers */
$publishers = [];
$pubQuery = $conn->query("SELECT pub_id, name FROM publisher");
while ($row = $pubQuery->fetch_assoc()) {
    $publishers[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book</title>
    <link rel="stylesheet" href="./addBook-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="./addBook-script.js" defer></script>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Admin Panel</h2> <!-- Optional: Add a title -->
        </div>
        <ul class="sidebar-menu">
            <li><a href="../admin-page/adminPage.php" id="home-link">Home</a></li>
            <li><a href="../admin-profile-view/profile.php" id="profile-link">Profile</a></li>
            <li><a href="../admin-search/search.php" id="search-link">Search</a></li>
            <li><a href="../admin-logout/logout.php" id="logout-link">Logout</a></li>
        </ul>
        <!-- Optional: Toggle button for mobile -->
        <button class="sidebar-toggle" id="sidebar-toggle">☰</button>
    </div>

    <main class="main-content">
        <!-- Each name should match the database column names -->
        <form id="addBookForm" action="../../Backend/addbookauthor.php" method="POST">

            <div class="segment">
                <h1>Add New Book</h1>
            </div>

            <label>
                <input type="text" placeholder="ISBN" id="isbn" name="book_isbn" maxlength="13" required />
            </label>

            <label>
                <input type="text" placeholder="Book Title" id="title" name="title" maxlength="225" required />
            </label>

            <label>
                <select id="pub_id" name="pub_id" required>
                    <option value="">Select Publisher</option>
                    <?php foreach ($publishers as $pub): ?>
                        <option value="<?= $pub['pub_id'] ?>">
                            <?= htmlspecialchars($pub['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>
                <select id="pub_year" name="pub_year" required>
                    <option value="">Publication Year</option>
                    <?php
                    $currentYear = date("Y");
                    for ($year = $currentYear; $year >= 1700; $year--) {
                        echo "<option value='$year'>$year</option>";
                    }
                    ?>
                </select>
            </label>

            <div class="form-group">
                <label for="num_authors">Number of Authors</label>
                <input type="number" id="num_authors" name="num_authors" min="1" value="1" required />
            </div>

            <!-- Dynamic author fields -->
            <div id="author_fields"></div>

            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    const numAuthorsInput = document.getElementById("num_authors");
                    const authorFieldsDiv = document.getElementById("author_fields");

                    function createAuthorFields() {
                        let numAuthors = parseInt(numAuthorsInput.value, 10);

                        if (isNaN(numAuthors) || numAuthors < 1) {
                            numAuthors = 1;
                            numAuthorsInput.value = 1;
                        }

                        // Clear existing fields
                        authorFieldsDiv.innerHTML = "";

                        // Create author inputs
                        for (let i = 0; i < numAuthors; i++) {
                            const wrapper = document.createElement("div");
                            wrapper.classList.add("form-group");

                            wrapper.innerHTML = `
                <label>
            <input type="text" placeholder="Author ${i + 1}" id="title" name="authors[]"  maxlength="225" required />
        </label>
            `;

                            authorFieldsDiv.appendChild(wrapper);
                        }
                    }

                    // Initialize on load
                    createAuthorFields();

                    // Update when number changes
                    numAuthorsInput.addEventListener("input", createAuthorFields);
                });
            </script>

            <label>
                <input type="number" id="price" name="price" step="0.01" placeholder="Selling Price" required />
            </label>

            <div class="form-group">
                <label for="quantity">Quantity in Stock</label>
                <input type="number" id="quantity" name="quantity" value="0" min="0" placeholder="Quantity in Stock" />
            </div>

            <div class="form-group">
                <label for="threshold">Minimum Stock Threshold</label>
                <input type="number" id="threshold" name="threshold" value="5" min="0"
                    placeholder="Minimum Stock Threshold" />
            </div>

            <label>
                <select id="category" name="category_id" required>
                    <option value="">Select Category</option>
                    <option value="1">Science</option>
                    <option value="2">Art</option>
                    <option value="3">Religion</option>
                    <option value="4">History</option>
                    <option value="5">Geography</option>
                </select>
            </label>

            <button class="red" type="submit">Add Book</button>

        </form>
    </main>
</body>

</html>