<?php
require "bookdb.php";
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['type'] !== 'Admin') {
    header("Location: ../Frontend/admin-login-view/login.php");
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

    //emptiness check
    if (
        empty($book_isbn) || empty($title) ||
        $pub_id <= 0 || $category_id <= 0 ||
        $selling_price <= 0 ||
        $quantity < 0 || $stock_threshold < 0 ||
        $quantity < $stock_threshold
    ) {
        die("Invalid input data.");
    }

    //isbn uniqueness check
    $checkISBN = $conn->prepare("SELECT 1 FROM book WHERE book_isbn = ?");
    $checkISBN->bind_param("s", $book_isbn);
    $checkISBN->execute();
    $checkISBN->store_result();
    if ($checkISBN->num_rows > 0)
        die("ISBN already exists.");
    $checkISBN->close();

    //publisher fk check
    $checkPub = $conn->prepare("SELECT 1 FROM publisher WHERE pub_id = ?");
    $checkPub->bind_param("i", $pub_id);
    $checkPub->execute();
    $checkPub->store_result();
    if ($checkPub->num_rows == 0)
        die("Invalid publisher.");
    $checkPub->close();

    //Category fk check
    $checkCat = $conn->prepare("SELECT 1 FROM category WHERE category_id = ?");
    $checkCat->bind_param("i", $category_id);
    $checkCat->execute();
    $checkCat->store_result();
    if ($checkCat->num_rows == 0)
        die("Invalid category.");
    $checkCat->close();

    //to ensure atomicity either both succeed or none
    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("
        INSERT INTO book
        (book_isbn, title, pub_id, pub_year, selling_price,
         quantity_in_stock, stock_threshold, category_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
        $stmt->bind_param(
            "ssiiddii",
            $book_isbn,
            $title,
            $pub_id,
            $pub_year,
            $selling_price,
            $quantity,
            $stock_threshold,
            $category_id
        );

        $stmt->execute();

        $authors = $_POST['authors'] ?? [];

        $checkAuthor = $conn->prepare(
            "SELECT author_id FROM author WHERE name = ?"
        );

        $insertAuthor = $conn->prepare(
            "INSERT INTO author (name) VALUES (?)"
        );

        $insertBookAuthor = $conn->prepare(
            "INSERT INTO book_author (book_isbn, author_id) VALUES (?, ?)"
        );

        foreach ($authors as $authorName) {
            $authorName = trim($authorName);
            if ($authorName === '')
                continue;

            // Check if author exists
            $checkAuthor->bind_param("s", $authorName);
            $checkAuthor->execute();
            $result = $checkAuthor->get_result();

            if ($row = $result->fetch_assoc()) {
                // Author already exists
                $author_id = $row['author_id'];
            } else {
                // Insert new author
                $insertAuthor->bind_param("s", $authorName);
                $insertAuthor->execute();
                $author_id = $insertAuthor->insert_id;
            }

            // Link book to author
            $insertBookAuthor->bind_param("si", $book_isbn, $author_id);
            $insertBookAuthor->execute();
        }

        $conn->commit();

        header("Location: ../Frontend/admin-page/adminPage.php");
        exit;

    } catch (Exception $e) {
        //ROLLBACK ON ERROR
        $conn->rollback();
        die("Failed to add book or author.");
    }
}
?>