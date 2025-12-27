<?php
require_once "bookdb.php";
if (isset($_POST["usermail"])) {
  $firstname = trim($_POST["firstname"]);
  $lastname = trim($_POST["lastname"]);
  $fullname = $firstname . " " . $lastname;
  $email = trim($_POST["usermail"]);
  $username = trim($_POST["username"]);
  $password = trim($_POST["userpass"]);
  $city = trim($_POST["useraddress-city"]);
  $street = trim($_POST["useraddress-street"]);
  $apt = trim($_POST["useraddress-apt"]);


  //validate emptiness
  if (empty($firstname) || empty($lastname) || empty($email) || empty($username) || empty($password) || empty($city) || empty($street) || empty($apt)) {
    die("<p style='color:red;'>All fields are required.</p>");
  }

  //validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("<p style='color:red;'>Invalid email format.</p>");
  }

  //validate new email (if already exists)
  $checkStmt = $conn->prepare("SELECT 1 FROM user WHERE email = ?");
  $checkStmt->bind_param("s", $email);
  $checkStmt->execute();
  $checkStmt->store_result();

  if ($checkStmt->num_rows > 0) {
    die("<p style='color:red;'>Email already exists!</p>");
  }
  $checkStmt->close();

  //validate pass length
  $passwordhash = password_hash($password, PASSWORD_DEFAULT);
  if (strlen($password) < 8) {
    die("<p style='color:red;'>Password must be at least 8 characters.</p>");
  }

  // Insert user into table
  $stmt = $conn->prepare(
    "INSERT INTO user (email, username, password, first_name, last_name, type, city, street, apartment) 
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
<<<<<<< HEAD
);

$type = isset($_POST['type']) ? $_POST['type'] : 'Customer';

$stmt->bind_param(
=======
  );

  $type = isset($_POST['type']) ? $_POST['type'] : 'Customer';

  $stmt->bind_param(
>>>>>>> e0ee7c46e097b88d389568bb9f3a27cfda2d98f2
    "sssssssss",
    $email,
    $username,
    $passwordhash,
    $firstname,
    $lastname,
    $type,
    $city,
    $street,
    $apt
<<<<<<< HEAD
);
=======
  );
>>>>>>> e0ee7c46e097b88d389568bb9f3a27cfda2d98f2

  if ($stmt->execute()) {
    echo "<p style='color:green;'>Registration successful!</p>";
  } else {
    echo "<p style='color:red;'>Registration failed. Please try again.</p>";
  }


  $stmt->close();
  $conn->close();
}
?>