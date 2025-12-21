<?php
require_once "../../Backend/bookdb.php";


if (isset($_POST['search'])) {


    $bookID = $_POST['bookID'];

    $stmt = $conn->prepare("
        SELECT DISTINCT
            b.book_isbn,
            b.title,
            b.pub_year,
            c.category_name,
            b.selling_price,
            b.quantity_in_stock,
            p.name AS publisher,
            a.name AS author
        FROM book b
        JOIN publisher p ON b.pub_id = p.pub_id
        JOIN category c ON b.category_id = c.category_id
        LEFT JOIN book_author ba ON b.book_isbn = ba.book_isbn
        LEFT JOIN author a ON ba.author_id = a.author_id
        WHERE b.book_isbn = ?"
    );

    $stmt->bind_param("s", $bookID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo json_encode(["success" => true, "data" => $result->fetch_assoc()]);

}
?>
