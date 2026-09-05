<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header('location:../files/login.php');
} else if ($_SESSION['user_role'] !== 'admin') {
    header('location:../files/index.php');
}
include 'databaseconnection.php';
$user_id = $_SESSION['user_id'];
if (isset($_GET['id'])) {
    $order_id = intval($_GET['id']);

    // fetch user details
    $user_query = "SELECT * FROM orders where id='$order_id'";
    $result1 = mysqli_query($conn, $user_query);

    // fetch order items
    $item_query = "SELECT products.name,products.image,order_items.quantity,order_items.price as op, order_items.product_id
    from products inner join order_items 
    on products.id = order_items.product_id 
    where order_items.order_id = '$order_id'";
    $result2 = mysqli_query($conn, $item_query);

    $conn->close();

}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

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
    }

    .card h3 {
        font-size: 28px;
        margin-bottom: 8px;
        color: #2563EB;
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

    /* =========================
       TABLE CARD
    ========================= */

    .table-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
        padding: 25px;
        margin-top: 25px;
        box-shadow: 0 5px 20px rgba(15, 23, 42, 0.04);
    }

    .table-card h3 {
        color: #0F172A;
        margin-bottom: 15px;
        font-size: 18px;
        font-weight: 700;
    }

    /* =========================
       TABLE WRAPPER
    ========================= */

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 0;
        background: #FFFFFF;
        overflow: hidden;
    }

    /* =========================
       TABLE HEADER
    ========================= */

    thead th {
        background: #1E293B;
        color: #FFFFFF;
        text-align: left;
        padding: 13px 14px;
        font-weight: 600;
        font-size: 14px;
        letter-spacing: 0.2px;
        white-space: nowrap;
    }

    /* =========================
       TABLE BODY
    ========================= */

    tbody td {
        padding: 13px 14px;
        border-bottom: 1px solid #E2E8F0;
        color: #64748B;
        vertical-align: middle;
        font-size: 14px;
    }

    tbody tr {
        transition: background 0.2s ease;
    }

    tbody tr:nth-child(even) {
        background: #F8FAFC;
    }

    tbody tr:hover {
        background: #EFF6FF;
    }

    tbody tr:last-child td {
        border-bottom: none;
    }

    /* =========================
       SMALL SUMMARY CELLS
    ========================= */

    .muted {
        color: #64748B;
        font-size: 13px;
        font-weight: 500;
    }

    /* =========================
       USER DETAILS TABLE
    ========================= */

    .user-details-table {
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        overflow: hidden;
    }

    .user-details-table td {
        padding: 12px 14px;
        vertical-align: top;
    }

    .user-details-table td:first-child {
        width: 35%;
        color: #1E293B;
        font-weight: 600;
    }

    .user-details-table tr:nth-child(odd) {
        background: #FFFFFF;
    }

    .user-details-table tr:nth-child(even) {
        background: #F8FAFC;
    }

    .user-details-table tr:hover {
        background: #EFF6FF;
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
       RESPONSIVE TABLE
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

        .table-card {
            padding: 20px;
        }
    }

    @media (max-width: 600px) {

        thead th,
        tbody td {
            padding: 10px 8px;
            font-size: 13px;
        }

        .table-card {
            padding: 16px;
        }

        .user-details-table td:first-child {
            width: 40%;
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

        .content {
            padding: 18px 15px;
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
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="view_users.php">Users</a></li>
                <li><a href="addproduct.php">Add Products</a></li>
                <li><a href="view_products.php">View Products</a></li>
                <li class="active"><a href="view_orders.php">View Orders</a></li>
            </ul>
        </aside>

        <!-- Main -->
        <div class="main">

            <div class="topbar">
                <h2>Order Details</h2>
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

            <!-- order details -->
            <div class="container">

                <!-- USER DETAILS TABLE -->
                <div class="card">
                    <h3>User Details</h3>
                    <div class="table-wrapper">
                        <table class="user-details-table">
                            <tbody>
                                <?php $row1 = mysqli_fetch_assoc($result1) ?>
                                    <tr>
                                        <td class="muted">Customer Name</td>
                                        <td><?php echo $row1['name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="muted">Email</td>
                                        <td><?php echo $row1['email']; ?></td>

                                    </tr>
                                    <tr>
                                        <td class="muted">Phone</td>
                                        <td><?php echo $row1['phone']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="muted">Shipping Address</td> 
                                        <td><?php echo $row1['address']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="muted">Total Amount</td>
                                        <td>Rs. <?php echo number_format($row1['total_amt']); ?></td>
                                    </tr>
                                     <tr>
                                        <td class="muted">Payment Method</td>
                                        <td><span style="background-color: #115e59; padding: 3px 5px; color: white; border-radius: 5px;"><?php echo $row1['payment_method']; ?></span></td>
                                    </tr>
                                     <tr>
                                        <td class="muted">Payment Status</td>
                                        <?php if($row1['payment_status'] == 'paid') { ?>
                                            <td><span class="badge completed" style="background-color: #115e59; padding: 3px 10px; color: white; border-radius: 5px;"><?php echo $row1['payment_status']; ?></span></td>
                                        <?php } else { ?>
                                            <td><span class="badge pending" style="background-color: #f97316; padding: 3px 10px; color: white; border-radius: 5px;"><?php echo $row1['payment_status']; ?></span></td>
                                        <?php } ?>
                                    </tr>
                               
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- PRODUCTS -->
                <div class="card table-card">

                    <h3>Ordered Products</h3>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Product ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total = 0;
                                while($row2 = mysqli_fetch_assoc($result2)) {
                                    //  $total += ($row2['quantity'] * $row2['op']);
                                    $item_total = $row2['quantity'] * $row2['op'];
                                ?>
                                    <tr>
                                        <td><?php echo $row2['product_id']; ?></td>
                                        <td><img src="../photos/<?php echo $row2['image']; ?>"
                                                alt="<?php echo $row2['name']; ?>" width="50"></td>
                                        <td><?php echo $row2['name']; ?></td>
                                        <td>Rs. <?php echo number_format($row2['op']); ?></td>
                                        <td><?php echo $row2['quantity']; ?></td>
                                       
                                        <td>Rs. <?php echo number_format($item_total); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>