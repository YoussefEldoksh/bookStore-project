<?php
require "bookdb.php";
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $book_isbn = trim($_POST['book_isbn']);
    $sold_qty = intval($_POST['sold_qty']);

    if (empty($book_isbn) || $sold_qty <= 0) {
        die("Invalid input.");
    }

    //Check current stock
    $stmt = $conn->prepare("
        SELECT quantity_in_stock 
        FROM book 
        WHERE book_isbn = ?
    ");
    $stmt->bind_param("s", $book_isbn);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Book not found.");
    }

    $row = $result->fetch_assoc();

    if ($row['quantity_in_stock'] < $sold_qty) {
        die("Not enough stock.");
    }

    //Reduce stock(TRIGGER WILL HANDLE REORDER)
    $update = $conn->prepare("
        UPDATE book
        SET quantity_in_stock = quantity_in_stock - ?
        WHERE book_isbn = ?
    ");
    $update->bind_param("is", $sold_qty, $book_isbn);
    $update->execute();

    echo "Book sold successfully. Stock updated.";
}

?>
<form method="POST">
    <label>Book ISBN:</label>
    <input type="text" name="book_isbn" required>

    <label>Quantity Sold:</label>
    <input type="number" name="sold_qty" min="1" required>

    <button type="submit">Confirm Sale</button>
</form>