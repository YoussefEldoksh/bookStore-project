<?php
require 'bookdb.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Customer') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// ---------- ADD TO CART ----------
if (isset($_POST['add_isbn'], $_POST['add_qty'])) {
    $isbn = $_POST['add_isbn'];
    $qty = intval($_POST['add_qty']);

    $stmt = $conn->prepare("INSERT INTO shopping_cart (user_id, book_isbn, quantity) VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)");
    $stmt->bind_param("isi", $user_id, $isbn, $qty);
    $stmt->execute();
}

// ---------- REMOVE FROM CART ----------
if (isset($_POST['remove_isbn'])) {
    $isbn = $_POST['remove_isbn'];

    $stmt = $conn->prepare("DELETE FROM shopping_cart WHERE user_id = ? AND book_isbn = ?");
    $stmt->bind_param("is", $user_id, $isbn);
    $stmt->execute();
}

// ---------- CHECKOUT ----------
$checkout_success = null;
if (isset($_POST['card_number'], $_POST['expiry_date'])) {
    $card = trim($_POST['card_number']);
    $expiry = trim($_POST['expiry_date']);

    // Simple validation
    if (strlen($card) === 16 && preg_match("/^\d{4}-\d{2}$/", $expiry)) {
        $conn->begin_transaction();
        try {
            // Calculate total
            $stmt = $conn->prepare("
                SELECT SUM(sc.quantity * b.selling_price) as total_price
                FROM shopping_cart sc
                JOIN book b ON sc.book_isbn = b.book_isbn
                WHERE sc.user_id = ?
            ");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $total_price = $result['total_price'];
            $stmt->close();

            //Insert into customer_order
            $stmt = $conn->prepare("
                INSERT INTO customer_order (user_id, total_amount, order_date, status, apartment, street_name, City, payment_method)
                VALUES (?, ?, NOW(), 'Delivered', 0, '', '', 'Credit Card')
            ");
            $stmt->bind_param("id", $user_id, $total_price);
            $stmt->execute();
            $order_id = $stmt->insert_id;
            $stmt->close();

            //Insert into customer_order_item and update stock
            $stmt = $conn->prepare("
                SELECT sc.book_isbn, sc.quantity, b.selling_price
                FROM shopping_cart sc
                JOIN book b ON sc.book_isbn = b.book_isbn
                WHERE sc.user_id = ?
            ");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $cart_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            $stmt_insert_item = $conn->prepare("
                INSERT INTO customer_order_item (order_id, book_isbn, quantity, unit_price)
                VALUES (?, ?, ?, ?)
            ");
            $stmt_update_stock = $conn->prepare("
                UPDATE book SET quantity_in_stock = quantity_in_stock - ? WHERE book_isbn = ?
            ");

            foreach ($cart_items as $item) {
                $stmt_insert_item->bind_param("isid", $order_id, $item['book_isbn'], $item['quantity'], $item['selling_price']);
                $stmt_insert_item->execute();

                $stmt_update_stock->bind_param("is", $item['quantity'], $item['book_isbn']);
                $stmt_update_stock->execute();
            }

            $stmt_insert_item->close();
            $stmt_update_stock->close();

            //Clear cart
            $stmt = $conn->prepare("DELETE FROM shopping_cart WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();

            $conn->commit();
            $checkout_success = true;

        } catch (Exception $e) {
            $conn->rollback();
            $checkout_success = false;
        }
    } else {
        $checkout_success = false;
    }
}

// ---------- FETCH CART ITEMS ----------
$stmt = $conn->prepare("
    SELECT sc.book_isbn, sc.quantity, b.title, b.selling_price
    FROM shopping_cart sc
    JOIN book b ON sc.book_isbn = b.book_isbn
    WHERE sc.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// ---------- CALCULATE TOTAL ----------
$total = 0;
$cart_items = [];
while ($row = $result->fetch_assoc()) {
    $row['subtotal'] = $row['quantity'] * $row['selling_price'];
    $total += $row['subtotal'];
    $cart_items[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
</head>
<body>
    <h1>My Shopping Cart</h1>

    <?php if ($checkout_success === true): ?>
        <p style="color:green;"><strong>Checkout successful!</strong></p>
    <?php elseif ($checkout_success === false): ?>
        <p style="color:red;"><strong>Checkout failed! Please check your credit card info.</strong></p>
    <?php endif; ?>

    <!-- ---------- ADD BOOK FORM ---------- -->
    <h2>Add Book to Cart</h2>
    <form method="POST">
        <input type="text" name="add_isbn" placeholder="Book ISBN" required>
        <input type="number" name="add_qty" placeholder="Quantity" min="1" required>
        <button type="submit">Add to Cart</button>
    </form>

    <!-- ---------- CART TABLE ---------- -->
    <h2>Cart Items</h2>
    <?php if (count($cart_items) === 0): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>ISBN</th>
                <th>Title</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Remove</th>
            </tr>
            <?php foreach ($cart_items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['book_isbn']) ?></td>
                    <td><?= htmlspecialchars($item['title']) ?></td>
                    <td><?= number_format($item['selling_price'], 2) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= number_format($item['subtotal'], 2) ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="remove_isbn" value="<?= $item['book_isbn'] ?>">
                            <button type="submit">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="4" align="right"><strong>Total:</strong></td>
                <td colspan="2"><strong><?= number_format($total, 2) ?></strong></td>
            </tr>
        </table>

        <!-- ---------- CHECKOUT FORM ---------- -->
        <h2>Checkout</h2>
        <form method="POST">
            <label>Credit Card Number:</label>
            <input type="text" name="card_number" maxlength="16" required><br>
            <label>Expiry Date (YYYY-MM):</label>
            <input type="text" name="expiry_date" placeholder="YYYY-MM" required><br>
            <button type="submit">Checkout</button>
        </form>
    <?php endif; ?>
</body>
</html>