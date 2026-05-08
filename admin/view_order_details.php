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

        /* Tables: user details and order items */
        .table-card h3 {
            color: #115e59;
            margin-bottom: 12px;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }

        thead th {
            background: linear-gradient(90deg, #115e59 0%, #0f766e 100%);
            color: #ffffff;
            text-align: left;
            padding: 12px 14px;
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 0.2px;
        }

        tbody td {
            padding: 12px 14px;
            border-bottom: 1px solid #e6fffb;
            color: #374151;
            vertical-align: middle;
            font-size: 14px;
        }

        tbody tr:nth-child(even) {
            background: #fbfffd;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        /* Small summary cells */
        .muted {
            color: #010101;
            font-size: 13px;
            font-weight: 500;
        }

        /* User details table (two-column) */
        .user-details-table td {
            padding: 10px 12px;
            vertical-align: top;
        }

        .user-details-table tr:nth-child(odd) {
            background: #ffffff;
        }

        .user-details-table tr:nth-child(even) {
            background: #fbfffd;
        }

        /* Payment badges */


        @media (max-width: 600px) {

            thead th,
            tbody td {
                padding: 10px 8px;
                font-size: 13px;
            }
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
                                        <td><span style="background-color: #115e59; padding: 3px 5px; color: white;"><?php echo $row1['payment_method']; ?></span></td>
                                    </tr>
                                     <tr>
                                        <td class="muted">Payment Status</td>
                                        <td><span class="badge <?php echo strtolower($row1['payment_status']); ?>"><?php echo $row1['payment_status']; ?></span></td>
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