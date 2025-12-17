<?php
require "bookdb.php";
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $order_id = intval($_POST['order_id']);

    if ($order_id <= 0) {
        die("Invalid order ID.");
    }

    //Start transaction
    $conn->begin_transaction();

    try {
        //Check order exists and not already received
        $check = $conn->prepare("
            SELECT status 
            FROM publisher_order 
            WHERE order_id = ?
        ");
        $check->bind_param("i", $order_id);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("Order not found.");
        }

        $order = $result->fetch_assoc();

        if ($order['status'] === 'Received') {
            throw new Exception("Order already confirmed.");
        }

        //mark order as received
        $updateOrder = $conn->prepare("
            UPDATE publisher_order
            SET status = 'Received', actual_delivery_date = CURDATE()
            WHERE order_id = ?
        ");
        $updateOrder->bind_param("i", $order_id);
        $updateOrder->execute();

        //get ordered items
        $items = $conn->prepare("
            SELECT book_isbn, quantity
            FROM publisher_order_item
            WHERE order_id = ?
        ");
        $items->bind_param("i", $order_id);
        $items->execute();
        $itemsResult = $items->get_result();

        //update stock for each book
        $updateStock = $conn->prepare("
            UPDATE book
            SET quantity_in_stock = quantity_in_stock + ?
            WHERE book_isbn = ?
        ");

        while ($row = $itemsResult->fetch_assoc()) {
            $updateStock->bind_param(
                "is",
                $row['quantity'],
                $row['book_isbn']
            );
            $updateStock->execute();
        }

        //commit everything
        $conn->commit();
        echo "Order confirmed and stock updated successfully.";

    } catch (Exception $e) {
        $conn->rollback();
        die("Error: " . $e->getMessage());
    }
}
?>

<form method="POST">
    <label>Publisher Order ID:</label>
    <input type="number" name="order_id" required>

    <button type="submit">Confirm Order</button>
</form>