<?php
require "bookdb.php";

if (isset($_GET['search'])) {

    $keyword = "%" . trim($_GET['search']) . "%";

    $stmt = $conn->prepare("
        SELECT DISTINCT
            b.book_isbn,
            b.title,
            c.category_name,
            b.selling_price,
            b.quantity_in_stock,
            p.name AS publisher
        FROM book b
        JOIN publisher p ON b.pub_id = p.pub_id
        JOIN category c ON b.category_id = c.category_id
        LEFT JOIN book_author ba ON b.book_isbn = ba.book_isbn
        LEFT JOIN author a ON ba.author_id = a.author_id
        WHERE b.book_isbn LIKE ?
           OR b.title LIKE ?
           OR c.category_name LIKE ?
           OR a.name LIKE ?
           OR p.name LIKE ?
    ");

    $stmt->bind_param("sssss", $keyword, $keyword, $keyword, $keyword, $keyword);
    $stmt->execute();
    $result = $stmt->get_result();

    echo json_encode(["status" => "success", "data" => $result->fetch_all(MYSQLI_ASSOC)]);
    exit;
}
?>



<?php
if (isset($result)) {
    while ($row = $result->fetch_assoc()) {
        echo "{$row['title']} | Stock: {$row['quantity_in_stock']}<br>";
    }
}
?>