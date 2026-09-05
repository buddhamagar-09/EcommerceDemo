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
        background: #F8FAFC;
        color: #0F172A;
        min-height: 100vh;
    }

   nav {
        background: #f6f7fa;
        padding: 22px 70px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        border-bottom: 1px solid #ced4dd;
        position: sticky;
        top: 0;
        z-index: 1000;
      
    }

    nav h2 {
        color: #010101;
        font-size: 28px;
        letter-spacing: 0.5px;
    }

    nav ul {
        list-style: none;
        display: flex;
        align-items: center;
        gap: 28px;
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
        border: 1px solid #CBD5E1;
        border-radius: 8px;
        outline: none;
        font-size: 15px;
        background: #FFFFFF;
        color: #0F172A;
        transition: border 0.2s ease, box-shadow 0.2s ease;
    }

    .nav-search input:focus {
        border-color: #2563EB;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    .nav-search button {
        border: none;
        border-radius: 8px;
        padding: 12px 18px;
        background: #2563EB;
        color: #FFFFFF;
        font-size: 15px;
        cursor: pointer;
        white-space: nowrap;
        transition: background 0.2s ease;
    }

    .nav-search button:hover {
        background: #1D4ED8;
    }

    nav ul li a {
        color: #1f1f20;
        text-decoration: none;
        font-size: 16px;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    nav ul li a:hover {
        color: #60A5FA;
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
        color: #1D4ED8;
        background: #EFF6FF;
        border: 1px solid #E2E8F0;
        border-radius: 999px;
        padding: 8px 14px;
        margin-bottom: 18px;
    }

    .breadcrumb a {
        color: #2563EB;
        text-decoration: none;
        font-weight: 600;
    }

    .product-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px;
        background: #FFFFFF;
        border-radius: 26px;
        display: grid;
        grid-template-columns: 1.1fr 1fr;
        gap: 60px;
        box-shadow: 0 22px 60px rgba(15, 23, 42, 0.12);
        border: 1px solid #E2E8F0;
    }

    /* IMAGE BOX */
    .product-image {
        width: 100%;
        aspect-ratio: 1/1;
        overflow: hidden;
        border-radius: 20px;
        background: #EFF6FF;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
        border: 1px solid #E2E8F0;
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
        color: #0F172A;
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
        border: 1px solid #E2E8F0;
        background: #EFF6FF;
        color: #1D4ED8;
    }

    .price {
        font-size: 32px;
        color: #2563EB;
        font-weight: 600;
        margin-bottom: 14px;
    }

    .desc {
        color: #64748B;
        line-height: 1.8;
        margin-bottom: 20px;
        max-width: 500px;
    }

    /* STOCK */
    .stock {
        width: fit-content;
        font-weight: 700;
        margin-bottom: 22px;
        color: #1D4ED8;
        background: #EFF6FF;
        border: 1px solid #E2E8F0;
        border-radius: 999px;
        padding: 8px 14px;
    }

    /* QUANTITY */
    .qty {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 22px;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        padding: 10px 12px;
        border-radius: 14px;
        width: fit-content;
    }

    .qty button {
        width: 35px;
        height: 35px;
        border: none;
        background: #EFF6FF;
        color: #1E293B;
        font-size: 18px;
        cursor: pointer;
        border-radius: 8px;
    }

    .qty button:hover {
        background: #DBEAFE;
    }

    .qty label {
        font-weight: 600;
        color: #1E293B;
    }

    .qty input {
        width: 80px;
        padding: 9px;
        text-align: center;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        font-weight: 600;
        color: #2563EB;
        background: #FFFFFF;
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
        color: #64748B;
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
        background: #2563EB;
        color: #FFFFFF;
        width: 100%;
        max-width: 250px;
        box-shadow: 0 10px 24px rgba(37, 99, 235, 0.24);
    }

    .buy:hover {
        background: #1D4ED8;
        transform: translateY(-2px);
        color: #FFFFFF;
        box-shadow: 0 8px 16px rgba(37, 99, 235, 0.30);
    }

    .buy:active {
        transform: translateY(0);
    }

    /* RATING */
    .rating {
        color: #2563EB;
        margin-bottom: 16px;
        letter-spacing: 2px;
        font-size: 17px;
    }

    footer {
        background: #ffffff;
        color: #000000;
        padding: 80px 70px;
        margin-top: 70px;
    }

    .footer-grid {
        max-width: 1200px;
        margin: auto;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 45px;
    }

    footer h4 {
        color: #FFFFFF;
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
        color: #36383a;
        border-top: 1px solid #9ea4ae;
        padding-top: 25px;
    }

    .logout_btn {
        background-color: #2563EB;
        color: #FFFFFF;
        padding: 8px 18px;
        font-weight: bold;
        text-decoration: none;
        border-radius: 20px;
    }

    .logout_btn:hover {
        background-color: #1D4ED8;
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
                <li style="color: black; font-weight: bold;">Welcome Back, <?php echo htmlspecialchars($_SESSION['user_name']); ?></li>
                <li><a href="cart.php" style="color: black; ">Cart</a></li>
                <li><a href="logout.php"
                        style="color: white; background: #2563EB; border-radius: 10px; font-size: large; padding: 5px 15px;">Logout</a>
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