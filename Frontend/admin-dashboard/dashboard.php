
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





?>