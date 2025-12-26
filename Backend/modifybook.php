<?php
require "bookdb.php";
session_start();

// Redirect to login page if not logged in or not an Admin
if (!isset($_SESSION['user_id']) || $_SESSION['type'] !== 'Admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_isbn = trim($_POST['book_isbn']);
    $title = trim($_POST['title']);
    $pub_year = intval($_POST['pub_year']);
    $selling_price = floatval($_POST['price']);
    $category_id = intval($_POST['category_id']);
    $sold_qty = intval($_POST['sold_qty']);

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Update book info
        $stmt = $conn->prepare("
            UPDATE book
            SET title = ?, pub_year = ?, selling_price = ?, category_id = ?
            WHERE book_isbn = ?
        ");
        $stmt->bind_param(
            "ssiid",
            $title, $pub_year, $selling_price, $category_id, $book_isbn
        );
        $stmt->execute();

        if ($selling_price <= 0) {
            $_SESSION['message'] = "Error: Selling price must be a positive value.";
            $_SESSION['message_type'] = "error";
            header("Location: ../Frontend/admin-manage-book/manageBook.php?book_isbn=" . $book_isbn);
            exit;
        }

        // Reduce stock if sold quantity is provided
        if ($sold_qty > 0) {
            $stmt2 = $conn->prepare("
                UPDATE book
                SET quantity_in_stock = quantity_in_stock - ?
                WHERE book_isbn = ?
            ");
            $stmt2->bind_param("is", $sold_qty, $book_isbn);
            $stmt2->execute();

            // Check if the stock doesn't go negative
            if ($stmt2->affected_rows == 0) {
                throw new Exception("Insufficient stock or invalid book ISBN.");
            }
        }

        // Commit the transaction
        $conn->commit();

        // Set success message
        $_SESSION['message'] = "Book updated successfully!";
        $_SESSION['message_type'] = "success";

        // Redirect to the page with success message
        header("Location: ../Frontend/admin-manage-book/manageBook.php?book_isbn=" . $book_isbn);
        exit;

    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();

        // Set error message
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['message_type'] = "error";

        // Redirect to the page with error message
        header("Location: ../Frontend/admin-manage-book/manageBook.php?book_isbn=" . $book_isbn);
        exit;
    }
}
?>


