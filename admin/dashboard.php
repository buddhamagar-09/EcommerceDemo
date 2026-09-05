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
        background: #F8FAFC;
        color: #0F172A;
    }

    /* =========================
       LAYOUT
    ========================= */

    .dashboard {
        display: flex;
        min-height: 100vh;
    }

    /* =========================
       SIDEBAR
    ========================= */

    .sidebar {
        width: 260px;
        background: #1E293B;
        color: #FFFFFF;
        padding: 30px 20px;
        position: fixed;
        height: 100%;
        left: 0;
        top: 0;
        box-shadow: 4px 0 18px rgba(15, 23, 42, 0.08);
    }

    .logo {
        font-size: 21px;
        font-weight: 700;
        margin-bottom: 40px;
        letter-spacing: 0.5px;
        padding: 0 5px;
    }

    .logo a {
        color: #FFFFFF;
        text-decoration: none;
    }

    .menu {
        list-style: none;
    }

    .menu li {
        margin-bottom: 8px;
    }

    .menu li a {
        text-decoration: none;
        color: #CBD5E1;
        display: block;
        padding: 13px 15px;
        border-radius: 8px;
        transition: 0.25s ease;
        font-size: 14px;
    }

    .menu li a:hover {
        background: #2563EB;
        color: #FFFFFF;
        transform: translateX(3px);
    }

    .menu li.active a {
        background: #2563EB;
        color: #FFFFFF;
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.20);
    }

    /* =========================
       NAV SEARCH
    ========================= */

    .nav-search {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1 1 320px;
        max-width: 420px;
        margin: 0 24px;
    }

    .nav-search input {
        width: 100%;
        min-width: 0;
        padding: 11px 15px;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        outline: none;
        font-size: 14px;
        color: #0F172A;
        transition: 0.25s ease;
    }

    .nav-search input:focus {
        background: #FFFFFF;
        border-color: #2563EB;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.10);
    }

    .nav-search button {
        border: none;
        border-radius: 8px;
        padding: 11px 18px;
        background: #2563EB;
        color: #FFFFFF;
        font-size: 14px;
        cursor: pointer;
        white-space: nowrap;
        transition: 0.25s ease;
    }

    .nav-search button:hover {
        background: #1D4ED8;
    }

    /* =========================
       MAIN
    ========================= */

    .main {
        margin-left: 260px;
        flex: 1;
        padding: 30px;
        min-width: 0;
    }

    /* =========================
       TOPBAR
    ========================= */

    .topbar {
        background: #FFFFFF;
        padding: 18px 25px;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.05);
    }

    .topbar h2 {
        font-weight: 700;
        color: #0F172A;
        font-size: 22px;
    }

    .profile {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .logout-btn {
        background: #2563EB;
        color: #FFFFFF;
        padding: 9px 18px;
        border-radius: 7px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: 0.25s ease;
    }

    .logout-btn:hover {
        background: #1D4ED8;
    }

    /* =========================
       STATS CARDS
    ========================= */

    .stats {
        margin-top: 25px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 18px;
    }

    .card {
        background: #FFFFFF;
        padding: 25px;
        border-radius: 10px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 5px 20px rgba(15, 23, 42, 0.04);
        transition: 0.25s ease;
        position: relative;
        overflow: hidden;
    }

    .card::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        width: 4px;
        height: 100%;
        background: #2563EB;
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
        border-color: #CBD5E1;
    }

    .card h3 {
        font-size: 28px;
        margin-bottom: 8px;
        color: #2563EB;
        font-weight: 700;
    }

    .card p {
        color: #64748B;
        font-size: 14px;
    }

    /* =========================
       CONTENT SECTION
    ========================= */

    .content {
        margin-top: 25px;
        background: #FFFFFF;
        padding: 30px;
        border-radius: 10px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 5px 20px rgba(15, 23, 42, 0.04);
        line-height: 1.8;
        color: #64748B;
    }

    .content h1,
    .content h2,
    .content h3,
    .content h4 {
        color: #0F172A;
    }

    /* =========================
       RESPONSIVE
    ========================= */

    @media (max-width: 992px) {
        .sidebar {
            width: 220px;
        }

        .main {
            margin-left: 220px;
            padding: 22px;
        }

        .topbar {
            flex-wrap: wrap;
        }

        .nav-search {
            order: 3;
            flex-basis: 100%;
            max-width: none;
            margin: 5px 0 0;
        }
    }

    @media (max-width: 768px) {

        .sidebar {
            position: relative;
            width: 100%;
            height: auto;
            padding: 20px;
        }

        .logo {
            margin-bottom: 20px;
        }

        .menu {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .menu li {
            margin-bottom: 0;
        }

        .menu li a {
            padding: 10px 13px;
        }

        .main {
            margin-left: 0;
            padding: 20px;
        }

        .topbar {
            padding: 16px 18px;
        }

        .stats {
            grid-template-columns: 1fr;
        }

        .content {
            padding: 22px 20px;
        }
    }

    @media (max-width: 500px) {

        .sidebar {
            padding: 18px 15px;
        }

        .logo {
            font-size: 20px;
        }

        .menu li a {
            font-size: 13px;
            padding: 9px 11px;
        }

        .main {
            padding: 15px;
        }

        .topbar {
            align-items: flex-start;
        }

        .nav-search {
            flex-direction: column;
            align-items: stretch;
        }

        .nav-search button {
            width: 100%;
        }

        .card {
            padding: 22px;
        }

        .content {
            padding: 20px 16px;
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