<?php
require_once '../../Backend/bookdb.php';
header('Content-Type: application/json');

// session_start();

// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Customer') {
//     header("Location: login.php");
//     exit;
// }

// $user_id = $_SESSION['user_id'];

// ---------- ADD TO CART ----------
// if (isset($_POST['add_isbn'], $_POST['add_qty'])) {
//     $isbn = $_POST['add_isbn'];
//     $qty = intval($_POST['add_qty']);

//     $stmt = $conn->prepare("INSERT INTO shopping_cart (user_id, book_isbn, quantity) VALUES (?, ?, ?)
//         ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)");
//     $stmt->bind_param("isi", $user_id, $isbn, $qty);
//     $stmt->execute();
// }

// // ---------- REMOVE FROM CART ----------
// if (isset($_POST['remove_isbn'])) {
//     $isbn = $_POST['remove_isbn'];

//     $stmt = $conn->prepare("DELETE FROM shopping_cart WHERE user_id = ? AND book_isbn = ?");
//     $stmt->bind_param("is", $user_id, $isbn);
//     $stmt->execute();
// }
// ---------- FETCH USER ID ----------
if (!isset($_POST['userId'])) {
    echo json_encode(['status' => 'fail', 'message' => 'User ID not provided']);
    exit;
}
$user_id = intval($_POST['userId']);

// ---------- FETCH USER ADDRESS ----------
$userStmt = $conn->prepare("
    SELECT apartment, street, city 
    FROM user 
    WHERE user_id = ?
");
$userStmt->bind_param("i", $user_id);
$userStmt->execute();
$userResult = $userStmt->get_result();
$user = $userResult->fetch_assoc();
$userStmt->close();

if (!$user) {
    echo json_encode(['status' => 'fail', 'message' => 'User not found']);
    exit;
}

$_useraprt   = $user['apartment'];
$_userstreet = $user['street'];
$_usercity   = $user['city'];
    
// ---------- CHECKOUT ----------
$checkout_success = null;
if (isset($_POST['cardNumber'], $_POST['expiryDate'])) {
    $card = trim($_POST['cardNumber']);
    $lastFour = substr($card, -4);
    $expiry = trim($_POST['expiryDate']);
    $user_id = $_POST['userId'];
    $total_price = ((float) $_POST['totalAmount']) - 10;
    // Store values in variables
    $_useraprt   = $user['apartment'];
    $_userstreet = $user['street'];
    $_usercity   = $user['city'];
    $cart_items = json_decode($_POST['cartArray'], true);

    // Simple validation

    $conn->begin_transaction();
    try {
        // Calculate total
        // $stmt = $conn->prepare("
        //     SELECT SUM(sc.quantity * b.selling_price) as total_price
        //     FROM shopping_cart sc
        //     JOIN book b ON sc.book_isbn = b.book_isbn
        //     WHERE sc.user_id = ?
        // ");
        // $stmt->bind_param("i", $user_id);
        // $stmt->execute();
        // $result = $stmt->get_result()->fetch_assoc();
        // $total_price = $result['total_price'];
        // $stmt->close();

    

        //Insert into customer_order
        $stmt = $conn->prepare("
                INSERT INTO customer_order (user_id, total_amount, order_date, status, apartment, street_name, City, payment_method,credit_card_last4)
                VALUES (?, ?, NOW(), 'Pending', ?, ?, ?, 'Credit Card', ?)
            ");
        $stmt->bind_param("idssss", $user_id, $total_price, $_useraprt, $_userstreet, $_usercity, $lastFour);
        $stmt->execute();
        $order_id = $stmt->insert_id;
        $stmt->close();

        //Insert into customer_order_item and update stock
        // $stmt = $conn->prepare("
        //     SELECT sc.book_isbn, sc.quantity, b.selling_price
        //     FROM shopping_cart sc
        //     JOIN book b ON sc.book_isbn = b.book_isbn
        //     WHERE sc.user_id = ?
        // ");
        // $stmt->bind_param("i", $user_id);
        // $cart_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        // $stmt->execute();
        // $stmt->close();

        $stmt_insert_item = $conn->prepare("
                INSERT INTO customer_order_item (order_id, book_isbn, quantity, unit_price , subtotal)
                VALUES (?, ?, ?, ?, ?)
            ");
        $stmt_update_stock = $conn->prepare("
                UPDATE book SET quantity_in_stock = quantity_in_stock - ? WHERE book_isbn = ?
            ");

        foreach ($cart_items as $item) {
            $subtot = (float) $item['quantity'] * (float) $item['price'];
            $stmt_insert_item->bind_param("isidd", $order_id, $item['bookId'], $item['quantity'], $item['price'], $subtot);
            $stmt_insert_item->execute();
            
            $stock_status = checkAndUpdateStock($conn, $item['bookId'], $item['quantity']);

            if ($stock_status === false) {
                // Not enough stock, decline the order and send an error message
                $conn->rollback();  // Rollback transaction
                echo json_encode(['status' => 'fail', 'message' => 'Not enough stock for book: ' . $item['bookId']]);
                exit;  // Stop further processing
            } elseif ($stock_status === 'low_stock') {
                // Stock is low, place an automatic restock order
                autoOrderIfStockLow($conn, $item['bookId'], $item['quantity']);
            }

             $stmt_update_stock->bind_param("is", $item['quantity'], $item['bookId']);
            $stmt_update_stock->execute();
        }

        $stmt_insert_item->close();
        $stmt_update_stock->close();

        //Clear cart
        // $stmt = $conn->prepare("DELETE FROM shopping_cart WHERE user_id = ?");
        // $stmt->bind_param("i", $user_id);
        // $stmt->execute();
        // $stmt->close();

        $conn->commit();
        echo json_encode(['status' => 'success', 'message' => 'Checkout successful']);
    } catch (Exception $e) {
        // Rollback transaction on failure
        $conn->rollback();
        echo json_encode(['status' => 'fail', 'message' => 'Checkout failed']);
    }

    // Clean up statements
    $stmt_insert_item->close();
    $stmt_update_stock->close();
}


// // ---------- FETCH CART ITEMS ----------
// $stmt = $conn->prepare("
//     SELECT sc.book_isbn, sc.quantity, b.title, b.selling_price
//     FROM shopping_cart sc
//     JOIN book b ON sc.book_isbn = b.book_isbn
//     WHERE sc.user_id = ?
// ");
// $stmt->bind_param("i", $user_id);
// $stmt->execute();
// $result = $stmt->get_result();

// // ---------- CALCULATE TOTAL ----------
// $total = 0;
// $cart_items = [];
// while ($row = $result->fetch_assoc()) {
//     $row['subtotal'] = $row['quantity'] * $row['selling_price'];
//     $total += $row['subtotal'];
//     $cart_items[] = $row;
// }


function checkAndUpdateStock($conn, $book_isbn, $quantity)
{
    // Get the current book stock
    $stmt = $conn->prepare("SELECT quantity_in_stock, stock_threshold FROM book WHERE book_isbn = ?");
    $stmt->bind_param("s", $book_isbn);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    //the quantity_in_stock is less than the quantity requested by the user
     // Check if book exists and if there's enough stock for the order
    if (!$book || $book['quantity_in_stock'] < $quantity) {
        return false;  // Not enough stock
    }

    // Check if after the order, the stock will fall below the threshold
    if ($book['quantity_in_stock'] - $quantity < $book['stock_threshold']) {
        return 'low_stock';  // Stock will fall below threshold after the order
    }


    return true;  // Stock updated successfully
}

function autoOrderIfStockLow($conn, $book_isbn, $quantity)
{
    // Get the current book info
    $stmt = $conn->prepare("SELECT quantity_in_stock, stock_threshold, pub_id FROM book WHERE book_isbn = ?");
    $stmt->bind_param("s", $book_isbn);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    if (!$book) {
        // Book not found, log this error
        error_log("Book with ISBN $book_isbn not found.");
        return false;
    }

    // Check if stock is below threshold
    if ($book['quantity_in_stock'] - $quantity < $book['stock_threshold']) {
        // Check if there is already a pending order for this book
        $checkStmt = $conn->prepare("
            SELECT order_id
            FROM publisher_order_item
            WHERE book_isbn = ? AND status = 'Pending'
        ");
        $checkStmt->bind_param("s", $book_isbn);
        $checkStmt->execute();
        $existingOrder = $checkStmt->get_result()->fetch_assoc();

        if ($existingOrder) {
            // There is already a pending order, log this case
            error_log("There is already a pending order for book $book_isbn.");
            return false;
        }

        // Insert new publisher_order_item
        $insertOrder = $conn->prepare("
            INSERT INTO publisher_order_item (pub_id, book_isbn, order_date, delivery_date, status, confirmed_by)
            VALUES (?, ?, CURDATE(), CURDATE(), 'Pending', NULL)
        ");

        // Bind parameters properly
        $insertOrder->bind_param("is", $book['pub_id'], $book_isbn);

        // Execute the query and check for success
        if ($insertOrder->execute()) {
            error_log("Publisher order for book $book_isbn created successfully.");
            return true; // Order created successfully
        } else {
            // Log failure with detailed error message
            error_log("Failed to create publisher order for book $book_isbn. Error: " . $insertOrder->error);
            return false;
        }
    }

    return false; // Stock is above threshold
}
?>
