<?php 
session_start();
include '../admin/databaseconnection.php';
$cart_count = 0;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $count_query = "Select count(*) as count from cart where user_id = $user_id";
    $count_result = mysqli_query($conn, $count_query);
    if ($count_result) {
        $count_row = mysqli_fetch_assoc($count_result);
        $cart_count = (int) ($count_row['count']);
    } else {
        $cart_count = 0;
    }
} else {
    $cart_count = 0;    
}
if(isset($_GET['id']))
{
    $product_id = intval($_GET['id']);
        $query = "Select * from Products where id = $product_id";
        $result = mysqli_query($conn,$query);
}
if (!isset($result) || !$result || mysqli_num_rows($result) === 0) {
    header('Location: products.php');
    exit();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        body {
            background: radial-gradient(circle at top right, #ccfbf1 0%, #f0fdfa 42%, #e6fffa 100%);
            color: #111;
            min-height: 100vh;
        }

        /* NAVBAR */
        /* ===== NAVBAR ===== */
        nav {
            background: linear-gradient(to right, #115e59, #0f766e);
            padding: 28px 70px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        nav h2 {
            color: #fff;
            font-size: 28px;
        }
        nav a {
            text-decoration: none;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 32px;
        }

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
            border: none;
            border-radius: 15px;
            outline: none;
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

        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
        }

        nav ul li a:hover {
            opacity: 0.8;
        }

        /* MAIN WRAPPER */
        .page-shell {
            max-width: 1250px;
            margin: 42px auto 80px;
            padding: 0 18px;
        }

        .breadcrumb {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #115e59;
            background: #ecfeff;
            border: 1px solid #b9efe6;
            border-radius: 999px;
            padding: 8px 14px;
            margin-bottom: 18px;
        }

        .breadcrumb a {
            color: #0f766e;
            text-decoration: none;
            font-weight: 600;
        }

        .product-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px;
            background: white;
            border-radius: 26px;
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            gap: 60px;
            box-shadow: 0 22px 60px rgba(15, 118, 110, 0.16);
            border: 1px solid #cff3eb;
        }

        /* IMAGE BOX (FIXED PROBLEM) */
        .product-image {
            width: 100%;
            aspect-ratio: 1/1;
            overflow: hidden;
            border-radius: 20px;
            background: linear-gradient(140deg, #ecfeff 0%, #dcfce7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
            border: 1px solid #bcefe5;
        }

        .product-image:hover {
            transform: scale(1.02);
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* DETAILS */
        .product-details {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .product-details h1 {
            font-size: 36px;
            margin-bottom: 12px;
            font-weight: 600;
            line-height: 1.2;
        }

        .meta-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 16px;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid #bdeee6;
            background: #f7fffd;
            color: #0f766e;
        }

        .price {
            font-size: 32px;
            color: #0f766e;
            font-weight: 600;
            margin-bottom: 14px;
        }


        .desc {
            color: #555;
            line-height: 1.8;
            margin-bottom: 20px;
            max-width: 500px;
        }

        /* STOCK */
        .stock {
            width: fit-content;
            font-weight: 700;
            margin-bottom: 22px;
            color: #166534;
            background: #dcfce7;
            border: 1px solid #86efac;
            border-radius: 999px;
            padding: 8px 14px;
        }

        /* QUANTITY */
        .qty {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 22px;
            background: #f9fffe;
            border: 1px solid #c8f1ea;
            padding: 10px 12px;
            border-radius: 14px;
            width: fit-content;
        }

        .qty button {
            width: 35px;
            height: 35px;
            border: none;
            background: #eee;
            font-size: 18px;
            cursor: pointer;
            border-radius: 8px;
        }

        .qty label {
            font-weight: 600;
            color: #115e59;
        }

        .qty input {
            width: 80px;
            padding: 9px;
            text-align: center;
            border: 1px solid #b8e9e1;
            border-radius: 8px;
            font-weight: 600;
            color: #0f766e;
            background: #fff;
        }

        /* CART FORM */
        .add-to-cart-form {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .usp-list {
            display: grid;
            grid-template-columns: 1fr;
            gap: 8px;
            margin: 6px 0 4px;
            color: #365048;
            font-size: 14px;
        }

        .usp-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        /* BUTTON */
        .buttons {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 14px 32px;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            transition: 0.3s all ease;
        }

        .buy {
            background: linear-gradient(135deg, #0f766e, #115e59);
            color: white;
            width: 100%;
            max-width: 250px;
            box-shadow: 0 10px 24px rgba(15, 118, 110, 0.24);
        }

        .buy:hover {
            background: #115e59;
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 8px 16px rgba(15, 118, 110, 0.3);
        }

        .buy:active {
            transform: translateY(0);
        }



        /* RATING */

        .rating {
            color: #f59e0b;
            margin-bottom: 16px;
            letter-spacing: 2px;
            font-size: 17px;
        }


        /* FOOTER */
        footer {
            background: #0f172a;
            color: #ccc;
            padding: 80px 70px;
        }

        .footer-grid {
            max-width: 1200px;
            margin: auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 45px;
        }

        footer h4 {
            color: white;
            margin-bottom: 20px;
        }

        footer ul {
            list-style: none;
        }

        footer ul li {
            margin-bottom: 12px;
            font-size: 14px;
        }

        .copy {
            text-align: center;
            margin-top: 55px;
            font-size: 14px;
            color: #aaa;
        }
               .logout_btn{
            background-color: #0f766e;
            color: white;
            padding: 8px 18px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 20px;
        }

        /* RESPONSIVE */
        @media(max-width:992px) {
            .product-wrapper {
                grid-template-columns: 1fr;
                gap: 40px;
                padding: 24px;
            }

            .product-details h1 {
                font-size: 28px;
            }
        }

        @media(max-width:600px) {
            nav {
                flex-direction: column;
                gap: 15px;
            }

            .page-shell {
                margin: 20px auto 60px;
            }

            .buy {
                max-width: 100%;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
</head>

<body>

   <!-- NAVBAR -->
  <nav>
        <h2>Sexy Wears</h2>
        <form class="nav-search" action="products.php" method="get">
            <input type="text" name="search" placeholder="Search products" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
            <button type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
        </form>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="contact.php">Contact</a></li>
            <?php if (isset($_SESSION['user_name']) && isset($_SESSION['user_email'])) { ?>
                <li style="color: white; font-weight: bold;">Welcome Back, <?php echo htmlspecialchars($_SESSION['user_name']); ?></li>
                <a href="cart.php" style="color: white; "><li class="fa-solid fa-cart-shopping"><?php if ($cart_count > 0) {
                    echo '<sup style="font-size: 0.82em; font-weight: 700; margin-left: 2px;">' . $cart_count . '</sup>';
                    } ?></li></a>
                <li><a href="logout.php"
                        style="color: white; background: #0f172a; border-radius: 20px; font-size: large; padding: 5px 15px;">Logout</a>
                </li>
            <?php } else { ?>
                <li><a href="register.php">Register</a></li>
                <li><a href="login.php">Login</a></li>
            <?php } ?>
        </ul>
    </nav>

<?php $row = mysqli_fetch_assoc($result); ?>
    <div class="page-shell">
        <div class="breadcrumb">
            <a href="index.php">Home</a>
            <span>/</span>
            <a href="products.php">Products</a>
            <span>/</span>
            <span><?php echo htmlspecialchars($row['name']); ?></span>
        </div>

    <!-- PRODUCT DETAIL -->
    <div class="product-wrapper">

        <!-- IMAGE -->
        <div class="product-image">
            <img src="../photos/<?php echo $row['image']; ?>" alt="">
        </div>

        <!-- DETAILS -->
        <div class="product-details">
            <h1><?php echo $row['name']; ?></h1>

            <div class="meta-row">
                <span class="pill"><i class="fa-solid fa-shield-heart"></i> Premium Quality</span>
                <span class="pill"><i class="fa-solid fa-truck-fast"></i> Fast Delivery</span>
            </div>

            <div class="price">Rs <?php echo $row['price']; ?></div>

            <div class="rating">★★★★★</div>


            <div class="desc">
               <?php echo $row ['description']; ?>
            </div>

            <div class="stock"><?php echo $row['quantity'] ?> items left</div>
            <div style="font-size: 12px; color: #0f766e; margin-bottom: 8px;"><?php echo ($row['quantity'] <= 0) ? 'Out of Stock' : 'In Stock'; ?></div>

            <div class="usp-list">
                <span class="usp-item"><i class="fa-solid fa-circle-check"></i> Easy return within 7 days</span>
                <span class="usp-item"><i class="fa-solid fa-lock"></i> Secure checkout experience</span>
            </div>

            <!-- Add to Cart Form -->
            <form action="cart.php" method="post" class="add-to-cart-form">
                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                <input type="hidden" name="action" value="add">
                
                <div class="qty">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $row['quantity']; ?>">
                </div>
                
                <button type="submit" class="btn buy card-btn add-cart-btn" <?php echo ($row['quantity'] <= 0) ? 'disabled' : ''; ?>>Add to Cart</button>
            </form>

        </div>

    </div>
    </div>

    <!-- FOOTER -->
    <footer>
        <div class="footer-grid">
            <div>
                <h4>Services</h4>
                <ul>
                    <li>Web Development</li>
                    <li>App Development</li>
                    <li>Digital Marketing</li>
                </ul>
            </div>
            <div>
                <h4>Social</h4>
                <ul>
                    <li>Facebook</li>
                    <li>Instagram</li>
                    <li>Twitter</li>
                </ul>
            </div>
            <div>
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li>Products</li>
                    <li>Contact</li>
                </ul>
            </div>
            <div>
                <h4>Contact</h4>
                <ul>
                    <li>Kathmandu, Nepal</li>
                    <li>info@gmail.com</li>
                    <li>98XXXXXXXX</li>
                </ul>
            </div>
        </div>

        <div class="copy">
            © 2026 My Ecom. All Rights Reserved.
        </div>
    </footer>

</body>

</html>