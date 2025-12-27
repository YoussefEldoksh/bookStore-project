<?php
session_start();
require "../../Backend/bookdb.php";

// Redirect to login page if not logged in or not an Admin
if (!isset($_SESSION['user_id']) || $_SESSION['type'] !== 'Admin') {
    header("Location: ../admin-login-view/login.php");
    exit;
}

$admin_id = $_SESSION['user_id'];
// Fetch pending publisher order items
$stmt = $conn->prepare("SELECT poi.order_id, poi.book_isbn, poi.delivery_date, b.title AS book_title
                        FROM publisher_order_item poi
                        JOIN book b ON poi.book_isbn = b.book_isbn
                        WHERE poi.status = 'Pending'");
$stmt->execute();
$publisherOrdersResult = $stmt->get_result();

// Fetch confirmed publisher order items
$stmt_confirmed = $conn->prepare("SELECT poi.order_id, poi.book_isbn, poi.delivery_date, b.title AS book_title, poi.status
                                  FROM publisher_order_item poi
                                  JOIN book b ON poi.book_isbn = b.book_isbn
                                  WHERE poi.status = 'Confirmed'");
$stmt_confirmed->execute();
$confirmedOrdersResult = $stmt_confirmed->get_result();

// Confirm the publisher order item and update its status and stock
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order_item'])) {
    $order_item_id = intval($_POST['order_item_id']); // Get the order item ID from the hidden input

    // Update publisher order item status to 'Confirmed'
    $stmt = $conn->prepare("UPDATE publisher_order_item
        SET status = 'Confirmed', confirmed_by = ?
        WHERE order_id = ?");
    $stmt->bind_param("ii", $admin_id, $order_item_id);
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        // Update book stock by adding 10
        $stmt_stock = $conn->prepare("UPDATE book SET quantity_in_stock = quantity_in_stock + 10
                                     WHERE book_isbn IN (SELECT book_isbn FROM publisher_order_item WHERE order_id = ?)");
        $stmt_stock->bind_param("i", $order_item_id);
        $stmt_stock->execute();

        // Check if stock update was successful
        if ($stmt_stock->affected_rows > 0) {
            $_SESSION['message'] = "Publisher order item confirmed and stock updated successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error updating stock.";
            $_SESSION['message_type'] = "error";
        }
    } else {
        $_SESSION['message'] = "Error confirming the publisher order item.";
        $_SESSION['message_type'] = "error";
    }

    // Redirect back to the orders page to reload the status
    header("Location: orders.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./orders-style.css">
    <script type="module" src="./orders-script.js" defer></script>
    <title>Admin Orders</title>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Admin Profile</h2>
        </div>
        <ul class="sidebar-menu">
            <li><a href="../admin-page/adminPage.php" id="home-link">Home</a></li>
            <li><a href="./adminProfile.php" id="profile-link">Profile</a></li>
            <li><a href="../admin-search/adminSearch.php" id="search-link">Search</a></li>
            <li>
                <a href="#" id="logout-link">
                    <i class="fa fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
        <!-- Optional: Toggle button for mobile -->
        <button class="sidebar-toggle" id="sidebar-toggle">☰</button>
    </div>

    <div class="main-content">
        <div class="order-tables-container">
            <!-- Pending Publisher Orders -->
            <div class="table-container pending">
                <h2 class="pending-orders-title">Pending Publisher Order Items</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>ISBN</th>
                            <th>Book Title</th>
                            <th>Delivery Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($orderItem = $publisherOrdersResult->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $orderItem['order_id'] ?></td>
                                <td><?= $orderItem['book_isbn'] ?></td>
                                <td><?= $orderItem['book_title'] ?></td>
                                <td><?= $orderItem['delivery_date'] ?></td>
                                <td>
                                    <!-- Confirm button for publisher order item -->
                                    <form method="POST" action="orders.php">
                                        <input type="hidden" name="order_item_id" value="<?= $orderItem['order_id'] ?>">
                                        <button type="submit" name="confirm_order_item" class="confirm-btn">Confirm</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Confirmed Publisher Orders -->
            <div class="table-container confirmed">
                <h2 class="confirmed-orders-title">Confirmed Publisher Order Items</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>ISBN</th>
                            <th>Book Title</th>
                            <th>Delivery Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($confirmedOrder = $confirmedOrdersResult->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $confirmedOrder['order_id'] ?></td>
                                <td><?= $confirmedOrder['book_isbn'] ?></td>
                                <td><?= $confirmedOrder['book_title'] ?></td>
                                <td><?= $confirmedOrder['delivery_date'] ?></td>
                                <td><?= $confirmedOrder['status'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="logout-modal" id="logout-modal">
        <div class="logout-box">
            <p>Are you sure you want to log out?</p>
            <div class="logout-actions">
                <button id="logout-yes">Yes</button>
                <button id="logout-no">No</button>
            </div>
        </div>
    </div>

</body>

</html>
