<?php
require 'bookdb.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Customer') {
    header("Location: login.php");
    exit;
}

$customer_id = $_SESSION['user_id'];

//get all past orders
$sql = "
    SELECT 
        co.order_id,
        co.order_date,
        coi.book_isbn,
        b.title,
        coi.quantity,
        coi.unit_price,
        (coi.quantity * coi.unit_price) AS subtotal,
        co.total_amount
    FROM customer_order co
    JOIN customer_order_item coi ON co.order_id = coi.order_id
    JOIN book b ON coi.book_isbn = b.book_isbn
    WHERE co.user_id = ?
    ORDER BY co.order_date DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
?>


