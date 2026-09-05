<?php
session_start();
include '../admin/databaseconnection.php';
$cart_count = 0;
include '../admin/databaseconnection.php';
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
if (isset($_GET['search'])) {
    $search_term = mysqli_real_escape_string($conn, $_GET['search']);
    $trimmed_search = trim($search_term);
    $sql = "SELECT * FROM products WHERE concat(name, description, price) LIKE '%$trimmed_search%'";
} else {
    $sql = "SELECT * FROM products";
}
$result = mysqli_query($conn, $sql);
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sexy Wears</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
   <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Segoe UI", sans-serif;
    }

    body {
        background: #F8FAFC;
        color: #0F172A;
    }

    /* ===== NAVBAR ===== */
    nav {
        background: #1E293B;
        padding: 28px 70px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    nav h2 {
        color: #FFFFFF;
        font-size: 28px;
    }

    nav ul {
        list-style: none;
        display: flex;
        gap: 32px;
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
    /* ===== HERO ===== */
    .hero {
        background: #2563EB;
        padding: 130px 20px;
        text-align: center;
        color: #FFFFFF;
    }

    .hero h1 {
        font-size: 62px;
        letter-spacing: 1px;
    }

    .hero p {
        margin: 18px auto;
        font-size: 20px;
        max-width: 650px;
        opacity: 0.95;
    }

    .hero-buttons {
        margin-top: 35px;
    }

    .hero-buttons button {
        padding: 15px 38px;
        font-size: 16px;
        border-radius: 30px;
        border: none;
        cursor: pointer;
        margin: 0 10px;
    }

    .hero-buttons .primary {
        background: #1E293B;
        color: #FFFFFF;
        font-weight: 600;
        border: 2px solid #FFFFFF;
    }

    .hero-buttons .primary:hover {
        background: #0F172A;
    }

    .hero-buttons .secondary {
        background: transparent;
        color: #FFFFFF;
        border: 2px solid #FFFFFF;
    }

    .hero-buttons .secondary:hover {
        background: #1D4ED8;
    }

    .hero-buttons button:hover {
        transform: translateY(-2px);
    }

    /* ===== PRODUCTS ===== */
    .products {
        padding: 80px 70px;
        background: #F8FAFC;
    }

    .products h2 {
        text-align: center;
        margin-bottom: 55px;
        font-size: 34px;
        color: #0F172A;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 35px;
    }

    .card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 22px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        transition: 0.3s;
        border: 1px solid #E2E8F0;
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 16px 35px rgba(15, 23, 42, 0.12);
    }

    .card img {
        width: 100%;
        height: 220px;
        object-fit: contain;
        background: #EFF6FF;
        border-radius: 12px;
    }

    .card h3 {
        margin: 18px 0 10px;
        font-size: 20px;
        color: #0F172A;
    }

    .card p {
        font-size: 14px;
        color: #64748B;
    }

    .price {
        margin: 15px 0;
        font-size: 18px;
        font-weight: bold;
        color: #2563EB;
    }

    .card-actions {
        display: grid;
        grid-template-columns: 1fr;
        gap: 10px;
        margin-top: 12px;
    }

    .card-btn {
        width: 100%;
        padding: 12px 18px;
        border: none;
        border-radius: 999px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
    }

    .card-btn:hover {
        transform: translateY(-2px);
    }

    .add-cart-btn {
        background: #2563EB;
        color: #FFFFFF;
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.22);
    }

    .add-cart-btn:hover {
        background: #1D4ED8;
    }

    .add-cart-btn:disabled {
        background: #CBD5E1;
        color: #64748B;
        cursor: not-allowed;
        box-shadow: none;
        opacity: 0.6;
    }

    .add-cart-btn:disabled:hover {
        background: #CBD5E1;
        transform: none;
    }

    .details-btn {
        background: #EFF6FF;
        color: #1D4ED8;
        border: 1px solid #E2E8F0;
    }

    .details-btn:hover {
        background: #DBEAFE;
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.12);
    }

    .no-products {
        min-height: 320px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border-radius: 12px;
        margin: 0 auto;
    }

    .no-products h2 {
        font-size: 22px;
        color: #0F172A;
        font-weight: 600;
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


    /* ===== RESPONSIVE ===== */
    @media(max-width:992px) {
        .product-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width:600px) {
        nav {
            justify-content: center;
            gap: 20px;
        }

        nav ul {
            flex-wrap: wrap;
            justify-content: center;
        }

        .product-grid {
            grid-template-columns: 1fr;
        }

        .hero h1 {
            font-size: 42px;
        }

        .nav-search {
            width: 100%;
            max-width: none;
            margin: 0;
        }

        .nav-search input {
            flex: 1;
        }
    }
</style>
</head>

<body>

    <!-- NAVBAR -->
    <nav>
        <h2>Sexy Wears</h2>

        <form class="nav-search" action="products.php" method="get">
            <input type="text" name="search" placeholder="Search products"
                value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
            <button type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
        </form>

        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="contact.php">Contact</a></li>
            <?php if (isset($_SESSION['user_name']) && isset($_SESSION['user_email'])) { ?>
                <li style="color: black; font-weight: bold;">Welcome Back,
                    <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                </li>
                <a href="cart.php" style="color: black; ">
                    <li class="fa-solid fa-cart-shopping"><?php if ($cart_count > 0) {
                        echo '<sup style="font-size: 0.82em; font-weight: 700; margin-left: 2px;">' . $cart_count . '</sup>';
                    } ?></li>
                </a>
                <li><a href="logout.php"
                        style="color: white; background: #2563EB; border-radius: 10px; font-size: large; padding: 5px 15px;">Logout</a>
                </li>
            <?php } else { ?>
                <li><a href="register.php">Register</a></li>
                <li><a href="login.php">Login</a></li>
            <?php } ?>
        </ul>
    </nav>
    <section class="products">

        <?php if (mysqli_num_rows($result) > 0) { ?>
            <h2>Our Products</h2>
            <div class="product-grid">
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <div class="card">
                        <img src="../photos/<?php echo $row['image']; ?>">
                        <h3><?php echo $row['name']; ?></h3>
                        <p><?php echo $row['description']; ?></p>
                        <div class="price">Rs.<?php echo $row['price']; ?></div>
                        <div style="font-size: 12px; color: #0f766e; margin-bottom: 8px;"><?php echo ($row['quantity'] <= 0) ? 'Out of Stock' : 'In Stock'; ?></div>
                        <div class="card-actions">
                            <form action="cart.php" method="post">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="card-btn add-cart-btn" <?php echo ($row['quantity'] <= 0) ? 'disabled' : ''; ?>>Add To Cart</button>
                            </form>
                            <a href="product_details.php?id=<?php echo $row['id']; ?>"><button type="button"
                                    class="card-btn details-btn">View Details</button></a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <div class="no-products">
                <h2 style="font-size: 30px; font-weight: bold;">No products found.</h2>
            </div>
        <?php } ?>
    </section>

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
                    <li><a href="products.php">Products</a></li>
                    <li><a href="contact.php">Contact</a></li>
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