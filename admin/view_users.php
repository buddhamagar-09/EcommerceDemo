<?php
session_start();
include 'databaseconnection.php';
if (!isset($_SESSION['user_email'])) {
  header('location:../files/login.php');
} else if ($_SESSION['user_role'] !== 'admin') {
  header('location:../files/index.php');
}
$sql = "select * from users where userrole = 'user' and status='active'";
$result = mysqli_query($conn, $sql);
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Users</title>

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
        width: 250px;
        height: 100vh;
        background: #1E293B;
        color: #FFFFFF;
        position: fixed;
        left: 0;
        top: 0;
        padding: 30px 20px;
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

    .menu li a:hover,
    .menu li.active a {
        background: #2563EB;
        color: #FFFFFF;
        transform: translateX(3px);
    }

    .menu li.active a {
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
        font-size: 14px;
        color: #0F172A;
        outline: none;
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
        margin-left: 250px;
        width: 100%;
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
        color: #0F172A;
        font-size: 21px;
        font-weight: 700;
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

    .profile {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    /* =========================
       TABLE
    ========================= */

    .table-container {
        margin-top: 25px;
        background: #FFFFFF;
        padding: 25px;
        border-radius: 10px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 5px 20px rgba(15, 23, 42, 0.04);
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 750px;
    }

    thead {
        background: #1E293B;
        color: #FFFFFF;
    }

    th {
        padding: 14px;
        text-align: left;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        letter-spacing: 0.2px;
    }

    td {
        padding: 14px;
        text-align: left;
        border-bottom: 1px solid #E2E8F0;
        font-size: 14px;
        color: #64748B;
        vertical-align: middle;
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
       DESCRIPTION / NAME
    ========================= */

    .description {
        max-width: 300px;
        line-height: 1.5;
    }

    .name {
        max-width: 150px;
        color: #0F172A;
        font-weight: 600;
    }

    /* =========================
       IMAGE
    ========================= */

    img {
        width: 120px;
        height: 120px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid #E2E8F0;
        background: #EFF6FF;
        padding: 3px;
    }

    /* =========================
       BUTTONS
    ========================= */

    .btn {
        padding: 7px 13px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
        margin-right: 5px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        transition: 0.2s ease;
    }

    /* Edit / Primary */

    .edit {
        background: #2563EB;
        color: #FFFFFF;
    }

    .edit:hover {
        background: #1D4ED8;
        transform: translateY(-1px);
    }

    /* Delete / Secondary */

    .delete {
        background: #1E293B;
        color: #FFFFFF;
    }

    .delete:hover {
        background: #0F172A;
        transform: translateY(-1px);
    }

    /* Delete User */

    .delusers {
        padding: 7px 13px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
        margin-right: 5px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #1E293B;
        color: #FFFFFF;
        transition: 0.2s ease;
    }

    .delusers:hover {
        background: #0F172A;
        transform: translateY(-1px);
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

        .table-container {
            padding: 18px;
        }
    }

    @media (max-width: 600px) {

        th,
        td {
            padding: 10px 8px;
            font-size: 13px;
        }

        img {
            width: 90px;
            height: 90px;
        }

        .table-container {
            padding: 15px;
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

        .table-container {
            padding: 12px;
        }

        .btn,
        .delusers {
            margin-right: 0;
            margin-bottom: 5px;
        }
    }
</style>
</head>

<body>
  <div class="dashboard">

    <!-- Sidebar -->

    <div class="sidebar">

      <div class="logo"><a href="../index.php">ECOM ADMIN</a></div>

      <ul class="menu">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li class="active"><a href="view_users.php">Users</a></li>
        <li><a href="addproductform.php">Add Products</a></li>
        <li><a href="view_products.php">View Products</a></li>
        <li><a href="view_orders.php">View Orders</a></li>
      </ul>

    </div>


    <!-- Main -->

    <div class="main">

      <div class="topbar">
        <h2>Users</h2>
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


      <!-- Product Table -->

      <div class="table-container">

        <table>

          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Address</th>
              <th>Phone</th>
              <th>Role</th>
              <th>Signed Date</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) {
              ?>
              <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email'] ?></td>
                <td><?php echo $row['address'] ?></td>
                <td><?php echo $row['phone'] ?></td>
                <td><?php echo $row['userrole'] ?></td>
                <td><?php echo $row['created_at'] ?></td>
                <td>
                  <a onclick="return confirm('Are you sure you want to delete the user ?')" class="delusers"
                    href="delete_users.php?id=<?php echo $row['id']; ?>">Delete</a>
                </td>
              </tr>
            <?php } ?>

          </tbody>
        </table>

      </div>

    </div>

  </div>

</body>

</html>