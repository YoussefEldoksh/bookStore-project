<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./adminPage-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script type="module" src="./adminPage-script.js" defer></script>
    <title>Admin Page</title>
</head>

<body>

    <div class="admin-container">
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <nav class="menu">
                <a href="#" data-section="home" class="active">
                    <i class="fa-solid fa-house"></i>
                </a>

                <a href="#" data-section="profile">
                    <i class="fa-solid fa-user"></i>
                </a>

                <a href="#" data-section="search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </a>

                <a href="#" data-section="logout">
                    <i class="fa-solid fa-log-out"></i>
                </a>
            </nav>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="content" id="content">
            <h1>Admin Dashboard</h1>
            <p>Welcome to the admin panel.</p>

            <div class="dashboard-cards">

                <div class="card" data-section="dashboard">
                    <i class="fa-solid fa-chart-line fa-2x"></i>
                    <h3>Dashboard</h3>
                    <p>View statistics and overview.</p>
                </div>

                <div class="card" data-section="add-book">
                    <i class="fa-solid fa-book-medical fa-2x"></i>
                    <h3>Add New Book</h3>
                    <p>Add books to your store.</p>
                </div>

                <div class="card" data-section="manage-books">
                    <i class="fa-solid fa-pen-to-square fa-2x"></i>
                    <h3>Modify Books</h3>
                    <p>Edit or remove existing books.</p>
                </div>

                <div class="card" data-section="orders">
                    <i class="fa-solid fa-box fa-2x"></i>
                    <h3>Orders</h3>
                    <p>Check and manage orders.</p>
                </div>

            </div>
        </main>

    </div>

</body>
</html>