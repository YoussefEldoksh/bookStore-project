
<?php
header('Content-Type: application/json');


// session_start();
// if (!isset($_SESSION['user_id']) || $_SESSION['type'] !== 'Admin') {
//     header("Location: ../admin-login-view/login.php");
//     exit;
// }

require_once "../../Backend/bookdb.php";


if (isset($_GET["orders"])) {

    $sql = "SELECT * FROM customer_order JOIN `user` ON customer_order.user_id = `user`.user_id";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) == 0) {
        echo json_encode(["success" => false, "message" => "No data retrived"]);
        mysqli_stmt_close($stmt);
        exit;
    }

    mysqli_stmt_close($stmt);

    echo json_encode(["success" => true, "data" => $result->fetch_all(MYSQLI_ASSOC)]);
}


if (isset($_GET["users"])) {

    $sql = "SELECT COUNT(*) AS userCount FROM `user`";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) == 0) {
        echo json_encode(["success" => false, "message" => "No data retrived"]);
        mysqli_stmt_close($stmt);
        exit;
    }

    mysqli_stmt_close($stmt);

    echo json_encode(["success" => true, "data" => $result->fetch_all(MYSQLI_ASSOC)]);
}

if (isset($_GET["TotalSalesPrevMonth"])) {

    $sql = "SELECT SUM(total_amount) AS total_sales 
FROM customer_order 
WHERE customer_order.order_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) 
  AND customer_order.order_date <= CURDATE()+1;
  ";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) == 0) {
        echo json_encode(["success" => false, "message" => "No data retrived"]);
        mysqli_stmt_close($stmt);
        exit;
    }

    mysqli_stmt_close($stmt);

    echo json_encode(["success" => true, "data" => $result->fetch_all(MYSQLI_ASSOC)]);
}
if (isset($_GET["TotalSalesToday"])) {

    $sql = "SELECT SUM(total_amount) AS total_sales 
FROM customer_order 
WHERE customer_order.order_date >= DATE_SUB(CURDATE(), INTERVAL 1 DAY) 
  AND customer_order.order_date <= CURDATE()+1;
  ";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) == 0) {
        echo json_encode(["success" => false, "message" => "No data retrived"]);
        mysqli_stmt_close($stmt);
        exit;
    }

    mysqli_stmt_close($stmt);

    echo json_encode(["success" => true, "data" => $result->fetch_all(MYSQLI_ASSOC)]);
}
if (isset($_GET["TotalSalesPrevMonthDetailed"])) {

    $sql = "SELECT *
FROM customer_order 
JOIN `user`
ON customer_order.user_id = `user`.user_id
WHERE customer_order.order_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) 
  AND customer_order.order_date <= CURDATE()+1
  ";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) == 0) {
        echo json_encode(["success" => false, "message" => "No data retrived"]);
        mysqli_stmt_close($stmt);
        exit;
    }

    mysqli_stmt_close($stmt);

    echo json_encode(["success" => true, "data" => $result->fetch_all(MYSQLI_ASSOC)]);
}
if (isset($_GET["TotalSalesPrevOnAGivenDay"])) {

    $date = trim($_GET['date']); // e.g., "2025-12-21"
    $date = str_replace('/', '-', $date);
    $sql = "SELECT *
FROM customer_order 
JOIN `user`
ON customer_order.user_id = `user`.user_id
WHERE DATE(customer_order.order_date) = ?
    ";

    $stmt = mysqli_prepare($conn, $sql);
    $stmt->bind_param("s", $date);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);


    mysqli_stmt_close($stmt);
    echo json_encode(["success" => true, "data" => $result->fetch_all(MYSQLI_ASSOC)]);
}


if (isset($_GET["Top5Cx"])) {

    $sql = "SELECT co.user_id, u.username, u.first_name, u.last_name, u.email, u.registration_date ,SUM(co.total_amount) AS total_spent
            FROM customer_order co
            JOIN `user` u ON co.user_id = u.user_id
              AND co.order_date >= NOW() - INTERVAL 3 MONTH
              AND co.order_date < NOW()
            GROUP BY co.user_id, u.username, u.first_name, u.last_name
            ORDER BY total_spent DESC
            LIMIT 5 ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);


    mysqli_stmt_close($stmt);
    echo json_encode(["success" => true, "data" => $result->fetch_all(MYSQLI_ASSOC)]);
}

if (isset($_GET["Top10Books"])) {

    $sql = "SELECT coi.book_isbn, b.title, p.name ,SUM(coi.quantity) AS total_bought
            FROM customer_order_item coi
            JOIN book b ON coi.book_isbn = b.book_isbn
            JOIN publisher as p ON b.pub_id = p.pub_id
            JOIN customer_order co ON coi.order_id = co.order_id
              AND co.order_date >= (NOW() - INTERVAL 3 MONTH)
              AND co.order_date < (NOW())
            GROUP BY coi.book_isbn, b.title
            ORDER BY total_bought DESC
            LIMIT 10
            
            ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);


    mysqli_stmt_close($stmt);
    echo json_encode(["success" => true, "data" => $result->fetch_all(MYSQLI_ASSOC)]);
}
if (isset($_GET["Replenishment"])) {

    $sql = "SELECT poi.book_isbn, b.title, COUNT(DISTINCT poi.order_id) AS times_ordered
            FROM publisher_order_item poi
            JOIN publisher_order po ON poi.order_id = po.order_id
            JOIN book b ON poi.book_isbn = b.book_isbn
            GROUP BY poi.book_isbn, b.title
            ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);


    mysqli_stmt_close($stmt);
    echo json_encode(["success" => true, "data" => $result->fetch_all(MYSQLI_ASSOC)]);
}





?>