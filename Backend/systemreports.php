<?php
require "bookdb.php";
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $report_type = $_POST['system_reports'];
    $book_isbn = trim($_POST['specific_book_isbn'] ?? '');

    if ($report_type == 'total_sales_month') {
        $stmt = $conn->prepare("
            SELECT SUM(total_amount) AS total_sales_month
            FROM customer_order
            WHERE status='Confirmed'
              AND order_date >= DATE_FORMAT(NOW() - INTERVAL 1 MONTH, '%Y-%m-01 00:00:00')
              AND order_date < DATE_FORMAT(NOW(), '%Y-%m-01 00:00:00')
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        echo "Total sales last month: " . $row['total_sales_month'];

    } elseif ($report_type == 'total_sales_day') {
        $stmt = $conn->prepare("
            SELECT SUM(total_amount) AS total_sales_day
            FROM customer_order
            WHERE status='Confirmed'
              AND order_date >= CURDATE()
              AND order_date < CURDATE() + INTERVAL 1 DAY
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        echo "Total sales today: " . $row['total_sales_day'];

    } elseif ($report_type == 'Top_Customers') {
        $stmt = $conn->prepare("
            SELECT co.user_id, u.username, u.first_name, u.last_name, SUM(co.total_amount) AS total_spent
            FROM customer_order co
            JOIN `user` u ON co.user_id = u.user_id
            WHERE co.status = 'Confirmed'
              AND co.order_date >= DATE_FORMAT(NOW() - INTERVAL 3 MONTH, '%Y-%m-01 00:00:00')
              AND co.order_date < DATE_FORMAT(NOW(), '%Y-%m-01 00:00:00')
            GROUP BY co.user_id, u.username, u.first_name, u.last_name
            ORDER BY total_spent DESC
            LIMIT 5
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
            echo "{$row['username']} ({$row['first_name']} {$row['last_name']}) - {$row['total_spent']}<br>";
        }

    } elseif ($report_type == 'Top_books') {
        $stmt = $conn->prepare("
            SELECT coi.book_isbn, b.title, SUM(coi.quantity) AS total_bought
            FROM customer_order_item coi
            JOIN book b ON coi.book_isbn = b.book_isbn
            JOIN customer_order co ON coi.order_id = co.order_id
            WHERE co.status='Delivered'
              AND co.order_date >= DATE_FORMAT(NOW() - INTERVAL 3 MONTH, '%Y-%m-01 00:00:00')
              AND co.order_date < DATE_FORMAT(NOW(), '%Y-%m-01 00:00:00')
            GROUP BY coi.book_isbn, b.title
            ORDER BY total_bought DESC
            LIMIT 10
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
            echo "{$row['title']} - {$row['total_bought']}<br>";
        }

    } elseif ($report_type == 'Total_times_for_book' && !empty($book_isbn)) {
        $stmt = $conn->prepare("
            SELECT poi.book_isbn, b.title, COUNT(DISTINCT poi.order_id) AS times_ordered
            FROM publisher_order_item poi
            JOIN publisher_order po ON poi.order_id = po.order_id
            JOIN book b ON poi.book_isbn = b.book_isbn
            WHERE poi.book_isbn = ?
              AND po.status = 'Confirmed'
            GROUP BY poi.book_isbn, b.title
        ");
        $stmt->bind_param("s", $book_isbn);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        echo "{$row['title']} was ordered {$row['times_ordered']} times by admin.";
    }
}
?>

<form method="POST">
    <label for="select_report">Select Report:</label>
    <select name="system_reports" id="select_report" required>
        <option value="total_sales_month">Total sales in the previous month</option>
        <option value="total_sales_day">Total sales in the previous day</option>
        <option value="Top_Customers">Top 5 Customers</option>
        <option value="Top_books">Top 10 Selling books</option>
        <option value="Total_times_for_book">Total times book was ordered</option>
    </select>

    <label for="book_isbn_totaltimesforbook">Enter specific book ISBN (only for the last report):</label>
    <input name="specific_book_isbn" type="text" placeholder="book_isbn">

    <button type="submit">Generate Report</button>
</form>
