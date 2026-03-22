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
            font-family: 'Inter', sans-serif;
        }

        body {
            background: #eef7f1;
        }

        /* Layout */
        .dashboard {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: #14532d;
            color: white;
            padding: 30px 20px;
            position: fixed;
            height: 100%;
        }

        .logo {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 40px;
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
            color: #d1fae5;
            display: block;
            padding: 12px 15px;
            border-radius: 8px;
            transition: 0.3s;
        }

        .menu li a:hover,
        .menu li.active a {
            background: #1f7a3d;
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

        .profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logout-btn {
            background: #101010;
            color: white;
            padding: 8px 18px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 14px;
        }

        /* Form Card */
        .form-card {
            margin-top: 30px;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.05);
            max-width: 700px;
        }

        /* Input Group */
        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #374151;
            font-weight: 500;
        }

        .input-group input,
        .input-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            outline: none;
            font-size: 14px;
            transition: 0.3s;
        }

        .input-group input:focus,
        .input-group textarea:focus {
            border-color: #1f7a3d;
            box-shadow: 0 0 0 3px rgba(31, 122, 61, 0.12);
        }

        textarea {
            resize: none;
            height: 100px;
        }

        /* Button */
        .btn {
            width: 100%;
            padding: 14px;
            background: #1f7a3d;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            background: #14532d;
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
            <div class="logo"><a href="../index.php">ECOM ADMIN</a></div>
            <ul class="menu">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="view_users.php">Users</a></li>
                <li class="active"><a href="#">Add Products</a></li>
                <li><a href="view_products.php">View Products</a></li>
            </ul>
        </aside>

        <!-- Main -->
        <div class="main">

            <div class="topbar">
                <h2>Edit Product</h2>
                <div class="profile">
                    <span>Admin</span>
                    <a href="../logout.php" class="logout-btn">Logout</a>
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