<?php
require "bookdb.php";
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../Frontend/admin-login-view/login.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $book_isbn = trim($_POST['book_isbn']);
    $title = trim($_POST['title']);
    $pub_id = intval($_POST['pub_id']);
    $pub_year = intval($_POST['pub_year']);
    $selling_price = floatval($_POST['price']);
    $stock_threshold = intval($_POST['threshold']);
    $category_id = intval($_POST['category_id']);
    $quantity = intval($_POST['quantity']);

    #emptiness check
    if (
    empty($book_isbn) || empty($title) ||
    $pub_id <= 0 || $category_id <= 0 ||
    $selling_price <= 0 ||
    $quantity < 0 || $stock_threshold < 0 ||
    $quantity < $stock_threshold
) {
    die("Invalid input data.");
}

    //isbn uniqueness
    $checkISBN = $conn->prepare("SELECT 1 FROM book WHERE book_isbn = ?");
    $checkISBN->bind_param("s", $book_isbn);
    $checkISBN->execute();
    $checkISBN->store_result();
    if ($checkISBN->num_rows > 0) die("ISBN already exists.");
    $checkISBN->close();

    //publisher fk check
    $checkPub = $conn->prepare("SELECT 1 FROM publisher WHERE pub_id = ?");
    $checkPub->bind_param("i", $pub_id);
    $checkPub->execute();
    $checkPub->store_result();
    if ($checkPub->num_rows == 0) die("Invalid publisher.");
    $checkPub->close();

    //Category fk check
    $checkCat = $conn->prepare("SELECT 1 FROM category WHERE category_id = ?");
    $checkCat->bind_param("i", $category_id);
    $checkCat->execute();
    $checkCat->store_result();
    if ($checkCat->num_rows == 0) die("Invalid category.");
    $checkCat->close();

    $stmt = $conn->prepare("
        INSERT INTO book
        (book_isbn, title, pub_id, pub_year, selling_price,
         quantity_in_stock, stock_threshold, category_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
    "ssiiddii",
    $book_isbn, $title, $pub_id, $pub_year,
    $selling_price, $quantity, $stock_threshold, $category_id
);
    $stmt->execute();
    header("Location: ../Frontend/admin-page/adminPage.php");
    exit;
}
?>

<form method="POST"> 
    <label for="isbn">Enter ISBN:</label> 
    <input name="book_isbn" type="text" placeholder="ISBN" required/> 
    <label for="title">Enter book title:</label> 
    <input name="title" type="text" placeholder="book title" required/> 
    <input name="pub_id" type="number" required/> 
    <input name="pub_year" type="number" required/> 
    <input name="price" type="number" placeholder="Enter price" required/> 
    <label>Minimum Stock Threshold:</label> 
    <input type="number" name="threshold" min="0" required> 
    <label for="category-select">Choose a Category:</label> 
    <select name="category_id" id="select_category" required>
        <option value="1">Science</option>
        <option value="2">Art</option> 
        <option value="3">Religion</option> 
        <option value="4">History</option> 
        <option value="5">Geography</option> 
    </select> <button type="submit">Add Book</button> 
</form>
