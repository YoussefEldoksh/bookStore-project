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

        if ($order['status'] === 'Confirmed') {
            throw new Exception("Order already confirmed.");
        }

        //mark order as received
        $updateOrder = $conn->prepare("
            UPDATE publisher_order
            SET status = 'Confirmed', actual_delivery_date = CURDATE()
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

function autoOrderIfStockLow($conn, $book_isbn, $fixed_quantity = 10) {
    //Get the current book info
    $stmt = $conn->prepare("SELECT quantity_in_stock, stock_threshold, pub_id FROM book WHERE book_isbn = ?");
    $stmt->bind_param("s", $book_isbn);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    if (!$book) {
        // Book not found
        return false;
    }
    
    //Check if stock is below threshold
    if ($book['quantity_in_stock'] < $book['stock_threshold']) {

        //Check if there is already a pending order for this book
        $checkStmt = $conn->prepare("
            SELECT poi.order_id
            FROM publisher_order_item poi
            JOIN publisher_order po ON poi.order_id = po.order_id
            WHERE poi.book_isbn = ? AND po.status = 'Pending'
        ");
        $checkStmt->execute([$book_isbn]);
        $existingOrder = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($existingOrder) {
            //There is already a pending order; do not create a new one
            return false;
        }

        //Insert new publisher_order
        $insertOrder = $conn->prepare("
            INSERT INTO publisher_order (pub_id, order_date, status, created_by)
            VALUES (?, CURDATE(), 'Pending', NULL)
        ");
        $insertOrder->execute([$book['pub_id']]);
        $order_id = $conn->lastInsertId();

        //Insert publisher_order_item
        $insertItem = $conn->prepare("
            INSERT INTO publisher_order_item (order_id, book_isbn, quantity, unit_cost)
            VALUES (?, ?, ?, 0)
        ");
        $insertItem->execute([$order_id, $book_isbn, $fixed_quantity]);

        return true; //Order created
    }

    return false; //Stock above threshold
}

?>

<form method="POST">
    <label>Publisher Order ID:</label>
    <input type="number" name="order_id" required>

    <button type="submit">Confirm Order</button>
</form>