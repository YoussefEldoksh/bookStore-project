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

// ---------- CHECKOUT ----------
$checkout_success = null;
if (isset($_POST['cardNumber'], $_POST['expiryDate'])) {
    $card = trim($_POST['cardNumber']);
    $lastFour = substr($card, -4);
    $expiry = trim($_POST['expiryDate']);
    $user_id = $_POST['userId'];
    $total_price = ((float)$_POST['totalAmount'])-10;
    $_useraprt = $_POST['apartment'];
    $_userstreet = $_POST['street'];
    $_usercity = $_POST['city'];
    $cart_items = json_decode($_POST['cartArray'],true);

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
            $stmt->bind_param("idssss", $user_id, $total_price,$_useraprt,$_userstreet,$_usercity, $lastFour);
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
                $subtot = (float)$item['quantity'] * (float)$item['price'];
                $stmt_insert_item->bind_param("isidd", $order_id, $item['bookId'], $item['quantity'], $item['price'], $subtot);
                $stmt_insert_item->execute();

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
            $checkout_success = true;

            echo json_encode(['status' => 'success', 'message' => 'data added successfully for order and order item']);
        } catch (Exception $e) {
            $conn->rollback();
            $checkout_success = false;
            echo json_encode(['status' => 'fail', 'message' => 'data failed test']);
        }
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
 ?>
