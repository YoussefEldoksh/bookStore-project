<?php
require "bookdb.php";
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $book_isbn = trim($_POST['book_isbn']);
    $title = trim($_POST['title']);
    $pub_id = intval($_POST['pub_id']);
    $pub_year = intval($_POST['pub_year']);
    $selling_price = floatval($_POST['price']);
    $category_id = intval($_POST['category_id']);
    $sold_qty = intval($_POST['sold_qty']);

    $conn->begin_transaction();

    try {
        //Update book info
        $stmt = $conn->prepare("
            UPDATE book
            SET title = ?, pub_id = ?, pub_year = ?, selling_price = ?, category_id = ?
            WHERE book_isbn = ?
        ");
        $stmt->bind_param(
            "siidss",
            $title, $pub_id, $pub_year, $selling_price, $category_id, $book_isbn
        );
        $stmt->execute();

        //Reduce stock
        if ($sold_qty > 0) {
            $stmt2 = $conn->prepare("
                UPDATE book
                SET quantity_in_stock = quantity_in_stock - ?
                WHERE book_isbn = ?
            ");
            $stmt2->bind_param("is", $sold_qty, $book_isbn);
            $stmt2->execute();
        }

        $conn->commit();
        header("Location: admin_dashboard.php");

    } catch (Exception $e) {
        $conn->rollback();
        die($e->getMessage());
    }
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
    <select name="category" id="select_category" required>
        <option value="Science">Science</option>
        <option value="Art">Art</option>
        <option value="Religion">Religion</option>
        <option value="History">History</option>
        <option value="Geography">Geography</option>
    </select>
    
    <button type="submit">Modify Book</button>
</form>