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
            color: #6b7280;
            font-size: 13px;
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
                    <tr>
                        <td class="muted">Customer Name</td>
                        <td>John Doe</td>
                    </tr>
                    <tr>
                        <td class="muted">Email</td>
                        <td>john@gmail.com</td>
                    </tr>
                    <tr>
                        <td class="muted">Phone</td>
                        <td>9800000000</td>
                    </tr>
                    <tr>
                        <td class="muted">Shipping Address</td>
                        <td>Kathmandu, Nepal</td>
                    </tr>
                    <tr>
                        <td class="muted">Payment Method</td>
                        <td>Cash On Delivery</td>
                    </tr>
                    <tr>
                        <td class="muted">Payment Status</td>
                        <td><span class="badge pending">Pending</span></td>
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
                    <th>Name</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>14</td>
                    <td>iPhone 14</td>
                    <td>Rs. 60</td>
                    <td>3</td>
                    <td>1800</td>
                </tr>
            </tbody>
        </table>
        </div>

    </div>

</div>

        </div>

    </div>

</body>

</html>