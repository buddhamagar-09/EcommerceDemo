<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header('location:../files/login.php');
} else if ($_SESSION['user_role'] !== 'admin') {
    header('location:../files/index.php');
}

include '../admin/databaseconnection.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: #f0fdfa;
        }

        /* Layout */
        .dashboard {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: #115e59;
            color: white;
            padding: 30px 20px;
            position: fixed;
            height: 100%;
        }

        .logo {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 40px;
            letter-spacing: 1px;
        }

        .logo a {
            color: white;
            text-decoration: none;
        }

        .menu {
            list-style: none;
        }

        .menu li {
            margin-bottom: 15px;
        }

        .menu li a {
            text-decoration: none;
            color: #99f6e4;
            display: block;
            padding: 12px 15px;
            border-radius: 8px;
            transition: 0.3s;
        }

        .menu li a:hover,
        .menu li.active a {
            background: #0f766e;
            color: white;
        }

        /* nav search */
        .nav-search {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1 1 320px;
            max-width: 420px;
            margin: 12px 24px;
        }

        .nav-search input {
            width: 100%;
            min-width: 0;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 15px;
            font-size: 15px;
        }

        .nav-search button {
            border: none;
            border-radius: 15px;
            padding: 12px 18px;
            background: #0f172a;
            color: #fff;
            font-size: 15px;
            cursor: pointer;
            white-space: nowrap;
        }

        .nav-search button:hover {
            opacity: 0.92;
        }

        /* Main */
        .main {
            margin-left: 260px;
            flex: 1;
            padding: 30px;
        }

        /* Topbar */
        .topbar {
            background: white;
            padding: 20px 30px;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .topbar h2 {
            font-weight: 600;
            color: #111827;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logout-btn {
            background: #0f172a;
            color: white;
            padding: 8px 18px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 14px;
        }

        /* Stats Cards */
        .stats {
            margin-top: 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.05);
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            font-size: 28px;
            margin-bottom: 8px;
            color: #115e59;
        }

        .card p {
            color: #6b7280;
            font-size: 14px;
        }

        /* Content Section */
        .content {
            margin-top: 30px;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.05);
            line-height: 1.8;
            color: #374151;
        }

        /* Responsive */
        @media(max-width:768px) {

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .main {
                margin-left: 0;
                padding: 20px;
            }

        }
    </style>
</head>

<body>

    <div class="dashboard">

        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo"><a href="dashboard.php">ECOM ADMIN</a></div>

            <ul class="menu">
                <li class="active"><a href="dashboard.php">Dashboard</a></li>
                <li><a href="view_users.php">Users</a></li>
                <li><a href="addproductform.php">Add Products</a></li>
                <li><a href="view_products.php">View Products</a></li>
                <li><a href="view_orders.php">View Orders</a></li>
            </ul>
        </aside>

        <!-- Main -->
        <div class="main">

            <div class="topbar">
                <h2>Dashboard</h2>
                <form class="nav-search" action="view_products.php" method="get">
                    <input type="text" name="search_product" placeholder="Search products"
                        value="<?php echo htmlspecialchars($_GET['search_product'] ?? ''); ?>">
                    <button type="submit" name="search" value="Search"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
                </form>
                <div class="profile">
                    <span>Admin</span>
                    <a href="../files/logout.php"
                        style="color: white; background: #0f172a; border-radius: 20px; font-size: large; padding: 5px 15px; text-decoration: none;">Logout</a>
                </div>
            </div>

            <!-- Stats -->
            <div class="stats">
                <div class="card">
                    <h3><?php
                    $user_query = "SELECT count(*) as count from users where userrole='user'";
                    $result = mysqli_query($conn, $user_query);
                    $row = mysqli_fetch_assoc($result);
                    echo $row['count'];
                    ?></h3>
                    <p>Total Users</p>
                </div>
                <div class="card">
                    <h3><?php
                    $product_query = "SELECT count(*) as count from products";
                    $result = mysqli_query($conn, $product_query);
                    $row = mysqli_fetch_assoc($result);
                    echo $row['count'];
                    ?></h3>
                    <p>Total Products</p>
                </div>
                <div class="card">
                    <h3><?php
                    $user_query = "SELECT count(*) as total_orders from orders";
                    $result = mysqli_query($conn, $user_query);
                    $row = mysqli_fetch_assoc($result);
                    echo $row['total_orders'];
                    ?></h3>
                    <p>Total Orders</p>
                </div>
                <div class="card">
                    <h3><?php
                    $user_query = "SELECT count(*) as paid_orders from orders where payment_status='paid'";
                    $result = mysqli_query($conn, $user_query);
                    $row = mysqli_fetch_assoc($result);
                    echo $row['paid_orders'];
                    ?></h3>
                    <p>Paid Orders</p>
                </div>
                <div class="card">
                    <h3>
                        <?php
                        $revenue_Query = "SELECT SUM(total_amt) as total_revenue from orders where payment_status='paid'";
                        $revenue_result = mysqli_query($conn, $revenue_Query);
                        $row = mysqli_fetch_assoc($revenue_result);
                        echo "Rs." . number_format($row['total_revenue']);
                        ?>
                    </h3>
                    <p>Total Revenue</p>
                </div>
            </div>

            <!-- Content -->
            <div class="content">
                <p style="text-align: justify;">
                    An eCommerce Admin Dashboard is a centralized management system designed to help store owners
                    monitor and control all business activities efficiently. It provides features such as product
                    management, order tracking, customer handling, sales analysis, and inventory monitoring in a single
                    interface. This demo dashboard is created to showcase how administrators can easily manage online
                    store operations with a user-friendly and organized system.
                </p>
            </div>

        </div>

    </div>

</body>

</html>