<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header('location:../files/login.php');
}
else if($_SESSION['user_role'] !== 'admin') {
    header('location:../files/index.php');
}

if(isset($_GET['id']))
    {
        $id = $_GET['id'];
        include 'databaseconnection.php';

        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $conn->close();
    }
    else{
        echo "no id provided";
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

   <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Inter", sans-serif;
    }

    body {
        background: #F8FAFC;
        color: #0F172A;
    }

    /* =========================
       DASHBOARD LAYOUT
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
        padding: 32px 18px;
        position: fixed;
        height: 100%;
        left: 0;
        top: 0;
        box-shadow: 4px 0 18px rgba(15, 23, 42, 0.08);
    }

    .logo {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 45px;
        padding: 0 12px;
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
        display: flex;
        align-items: center;
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
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.22);
    }

    /* =========================
       MAIN CONTENT
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
       FORM HEADER
    ========================= */

    .form-card::before {
        content: "Add Product";
        display: block;
        font-size: 24px;
        font-weight: 700;
        color: #0F172A;
        margin-bottom: 6px;
    }

    .form-card::after {
        content: "Add a new product to your store.";
        display: block;
        color: #64748B;
        font-size: 14px;
        margin-bottom: 28px;
    }

    /* =========================
       FORM CARD
    ========================= */

    .form-card {
        margin-top: 25px;
        background: #FFFFFF;
        padding: 35px;
        border-radius: 10px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 6px 22px rgba(15, 23, 42, 0.05);
        max-width: 760px;
    }

    /* =========================
       INPUT GROUP
    ========================= */

    .input-group {
        margin-bottom: 22px;
    }

    .input-group label {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        color: #1E293B;
        font-weight: 600;
    }

    .input-group input,
    .input-group textarea,
    .input-group select {
        width: 100%;
        padding: 13px 14px;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 7px;
        outline: none;
        font-size: 14px;
        color: #0F172A;
        transition: 0.25s ease;
    }

    .input-group input::placeholder,
    .input-group textarea::placeholder {
        color: #94A3B8;
    }

    .input-group input:hover,
    .input-group textarea:hover,
    .input-group select:hover {
        border-color: #CBD5E1;
    }

    .input-group input:focus,
    .input-group textarea:focus,
    .input-group select:focus {
        background: #FFFFFF;
        border-color: #2563EB;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.10);
    }

    textarea {
        resize: vertical;
        min-height: 120px;
        line-height: 1.6;
    }

    /* =========================
       BUTTON
    ========================= */

    .btn {
        width: 100%;
        padding: 14px;
        background: #2563EB;
        color: #FFFFFF;
        border: none;
        border-radius: 7px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.25s ease;
        margin-top: 5px;
    }

    .btn:hover {
        background: #1D4ED8;
        transform: translateY(-1px);
        box-shadow: 0 7px 18px rgba(37, 99, 235, 0.20);
    }

    .btn:active {
        transform: translateY(0);
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
       RESPONSIVE
    ========================= */

    @media (max-width: 900px) {
        .sidebar {
            width: 220px;
        }

        .main {
            margin-left: 220px;
            padding: 22px;
        }

        .form-card {
            max-width: 100%;
        }
    }

    @media (max-width: 768px) {
        .dashboard {
            display: block;
        }

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
            flex-wrap: wrap;
            padding: 16px 18px;
        }

        .nav-search {
            order: 3;
            flex-basis: 100%;
            max-width: none;
            margin: 5px 0 0;
        }

        .form-card {
            margin-top: 20px;
            padding: 25px 20px;
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

        .form-card {
            padding: 22px 16px;
        }

        .form-card::before {
            font-size: 21px;
        }

        .nav-search {
            flex-direction: column;
            align-items: stretch;
        }

        .nav-search button {
            width: 100%;
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
                <li class="active"><a href="addproductform.php">Add Products</a></li>
                <li><a href="view_products.php">View Products</a></li>
            </ul>
        </aside>

        <!-- Main -->
        <div class="main">

            <div class="topbar">
                <h2>Edit Product</h2>
                   <form class="nav-search" action="view_products.php" method="get">
                    <input type="text" name="search_product" placeholder="Search products"
                        value="<?php echo htmlspecialchars($_GET['search_product'] ?? ''); ?>">
                    <button type="submit" name="search" value="Search"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
                </form>
                 <div class="profile">
                    <span>Admin</span>
                    <a href="../files/logout.php" style="color: white; background: #0f172a; border-radius: 20px; font-size: large; padding: 5px 15px; text-decoration: none;">Logout</a>
                </div>
            </div>

            <!-- Form -->
            <div class="form-card">
                <?php while($product = mysqli_fetch_assoc($result)){ ?>
                <form action="editproduct.php" method="post" enctype="multipart/form-data">

                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($product['image']); ?>">

                    <div class="input-group">
                        <label>Product Name</label>
                        <input type="text" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                    </div>

                    <div class="input-group">
                        <label>Description</label>
                        <textarea name="product_description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                    </div>
                    <div class="input-group">
                        <label>Price</label>
                        <input type="number" name="product_price" value="<?php echo $product['price']; ?>"  required>
                    </div>

                    <div class="input-group">
                        <label>Quantity</label>
                        <input type="number" name="product_quantity" value="<?php echo $product['quantity']; ?>" required>
                    </div>

                    <div class="input-group">
                        <label>Product Image</label>
                            <div style="margin-bottom:10px;">
                                <p style="font-size:13px;color:#6b7280;margin-bottom:6px;">Current image:</p>
                                <img src="../photos/<?php echo htmlspecialchars($product['image']); ?>" alt="Current product image" style="max-width:150px;max-height:150px;border-radius:8px;border:1px solid #d1d5db;">
                            </div>
                        <input type="file" name="product_image" accept="image/*">
                        <small style="color:#6b7280;">Leave empty to keep the current image.</small>
                    </div>

                    <button type="submit" name="editproduct" class="btn">Update Product</button>

                </form>
             <?php } ?>

        </div>

    </div>

</body>

</html>