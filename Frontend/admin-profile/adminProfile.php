<?php
session_start();
require "../../Backend/bookdb.php";

if (!isset($_SESSION['user_id']) || $_SESSION['type'] !== 'Admin') {
    header("Location: ../admin-login-view/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("
    SELECT email, first_name, last_name, username, city, street, apartment
    FROM `user`
    WHERE user_id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

$stmt->bind_result(
    $email,
    $first_name,
    $last_name,
    $username,
    $city,
    $street,
    $apartment
);
$stmt->fetch();

$user = [
    'email' => $email,
    'first_name' => $first_name,
    'last_name' => $last_name,
    'username' => $username,
    'city' => $city,
    'street' => $street,
    'apartment' => $apartment
];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./adminProfile-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script type="module" src="./adminProfile-script.js" defer></script>
    <title>Admin Profile</title>
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
        <div class="form-card">
            <h2 class="right-div-title">Admin Profile</h2>
            <form method="post" action="editprofile.php" class="profile-form">

                <div class="form-row">
                    <div>
                        <label>First Name</label>
                        <input type="text" class="form-input" name="firstname"
                            value="<?php echo htmlspecialchars($user['first_name']); ?>"
                            required>
                    </div>
                    <div>
                        <label>Last Name</label>
                        <input type="text" class="form-input" name="lastname"
                            value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                    </div>
                </div>

                <label>Username</label>
                <input type="text" class="form-input" name="username" value="<?php echo htmlspecialchars($user['username']); ?>"
                    required>

                <label>Email</label>
                <input type="email" class="form-input" name="usermail" value="<?php echo htmlspecialchars($user['email']); ?>"
                    required>

                <div class="form-row">
                    <div>
                        <label>City</label>
                        <input type="text" class="form-input" name="useraddress-city"
                            value="<?php echo htmlspecialchars($user['city']); ?>" required>
                    </div>
                    <div>
                        <label>Street</label>
                        <input type="text" class="form-input" name="useraddress-street"
                            value="<?php echo htmlspecialchars($user['street']); ?>" required>
                    </div>
                    <div>
                        <label>Apartment</label>
                        <input type="text" class="form-input" name="useraddress-apt"
                            value="<?php echo htmlspecialchars($user['apartment']); ?>" required>
                    </div>
                </div>

                <label>Password (leave empty to keep)</label>
                <input type="password" class="form-input" name="userpass">

                <div class="form-actions">
                    <button type="reset" class="discard-btn">Discard</button>
                    <button type="submit" class="continue-btn">Save Changes</button>
                </div>

            </form>

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