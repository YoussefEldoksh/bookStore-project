<?php
include '';  
require "bookdb.php";
session_start();
if (!isset($_SESSION['user_id'])) header("Location: login.php");

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_isbn = $_POST['book_isbn'];
    $title = $_POST['title'];
    $pub_id = intval($_POST['pub_id']);
    $pub_year = intval($_POST['year']);
    $category = $_POST['category'];
    $selling_price = floatval($_POST['price']);

    $stmt = $conn->prepare("
    SELECT FROM Book (book_isbn, title, pub_id, pub_year, selling_price, category) VALUES (?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("ssiids",
    $book_isbn,
    $title,
    $pub_id,
    $pub_year,
    $selling_price,
    $category);

    header("Location: .php"); //redirection page
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
    <label for="category-select">Choose a Category:</label>
    <select name="category" id="select_category" required>
        <option value="Science">Science</option>
        <option value="Art">Art</option>
        <option value="Religion">Religion</option>
        <option value="History">History</option>
        <option value="Geography">Geography</option>
    </select>
    
    <button type="submit">Add Book</button>
</form>