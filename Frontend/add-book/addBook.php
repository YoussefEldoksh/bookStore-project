<?php
session_start();
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
    <link rel="stylesheet" href="../CSS/addBook-style.css">
    <script src="../JS/addBook-script.js" defer></script>
</head>

<body>
    <div class="addbook-container">
        <h1>Add New Book</h1>
        <!-- Each name should match the database column names -->
        <form id="addBookForm" action="../../Backend/addBook.php" method="POST">
            <div class="form-group">
                <label for="isbn">Book ISBN</label>
                <input type="text" id="isbn" name="book_isbn" maxlength="13" required>
            </div>

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" maxlength="225" required>
            </div>

            <div class="form-group">
                <label for="pub_id">Publisher</label>
                <select id="pub_id" name="pub_id" required>
                    <option value="">Select Publisher</option> <!-- Only one static placeholder -->

                    <?php foreach ($publishers as $pub): ?>
                        <option value="<?= $pub['pub_id'] ?>">
                            <?= htmlspecialchars($pub['name']) ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>

            <div class="form-group">
                <label for="pub_year">Publication Year</label>
                <select id="pub_year" name="pub_year" required>
                    <option value="">Select Year</option>
                    <?php
                    $currentYear = date("Y");
                    for ($year = $currentYear; $year >= 1700; $year--) {
                        echo "<option value='$year'>$year</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="price">Selling Price</label>
                <input type="number" id="price" name="selling_price" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity in Stock</label>
                <input type="number" id="quantity" name="quantity_in_stock" value="0" min="0">
            </div>

            <div class="form-group">
                <label for="threshold">Minimum Stock Threshold</label>
                <input type="number" id="threshold" name="stock_threshold" value="5" min="0">
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category_id" required>
                    <option value="">Select Category</option>
                    <option value="1">Science</option>
                    <option value="2">Art</option>
                    <option value="3">Religion</option>
                    <option value="4">History</option>
                    <option value="5">Geography</option>
                </select>
            </div>

            <button type="submit">Add Book</button>
        </form>
    </div>
</body>

</html>