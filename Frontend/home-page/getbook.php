<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Log what we're receiving
file_put_contents('debug.log', "POST data: " . print_r($_POST, true) . "\n", FILE_APPEND);

require "../../Backend/bookdb.php";
mysqli_set_charset($conn, "utf8mb4");

if (isset($_POST['submit'])) {
    $category = isset($_POST['category']) ? $_POST['category'] : '';
    
    // Debug: Log the category value
    file_put_contents('debug.log', "Category received: '$category'\n", FILE_APPEND);

    if (!empty($category)) {
        // Filter by category
        $stmt = $conn->prepare("
            SELECT DISTINCT
                b.book_isbn,
                b.title,
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
            WHERE c.category_name = ?
        ");
        
        if (!$stmt) {
            // echo json_encode(["success" => false, "message" => "Database error: " . mysqli_error($conn)]);
            exit;
        }
        
        $stmt->bind_param("s", $category);
        
    } else {
        // Return all books (no category selected)
        $stmt = $conn->prepare("
            SELECT DISTINCT
                b.book_isbn,
                b.title,
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
        ");
        
        if (!$stmt) {
            // echo json_encode(["success" => false, "message" => "Database error: " . mysqli_error($conn)]);
            exit;
        }
    }

    if (!$stmt->execute()) {
        file_put_contents('debug.log', "Execute error: " . $stmt->error . "\n", FILE_APPEND);
        // echo json_encode(["success" => false, "message" => "Query execution error: " . $stmt->error]);
        exit;
    }
    
    $result = $stmt->get_result();
    
    if (!$result) {
        file_put_contents('debug.log', "Result error: " . mysqli_error($conn) . "\n", FILE_APPEND);
        // echo json_encode(["success" => false, "message" => "Get result error: " . mysqli_error($conn)]);
        exit;
    }
    
    $books = [];
    $rowCount = 0;

    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
        $rowCount++;
    }

    
    // Debug: Log how many rows were fetched
    
    file_put_contents('debug.log', "Rows fetched: $rowCount\n", FILE_APPEND);
file_put_contents('debug.log', "Data fetched: " . json_encode($books, JSON_PRETTY_PRINT) . "\n", FILE_APPEND);    echo json_encode(["success" => true, "data" => $books]);
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "submit parameter not set. Received POST: " . json_encode($_POST)]);
}
?>