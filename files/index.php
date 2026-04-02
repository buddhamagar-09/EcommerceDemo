<?php
session_start();
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Ecom</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", sans-serif;
        }

        body {
            background: #f0fdfa;
        }

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

        nav ul {
            list-style: none;
            display: flex;
            gap: 32px;
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

        /* ===== HERO (PROFESSIONAL) ===== */
        .hero {
            background: linear-gradient(135deg, #14b8a6, #0f766e);

            padding: 130px 20px;
            text-align: center;
            color: white;
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
            background: #0f172a;
            color: #fff;
            font-weight: 600;
            padding: 15px 38px;
            font-size: 16px;
            border-radius: 30px;
            border: 2px solid #fff;
            text-decoration: none;
        }

        .hero-buttons .secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .hero-buttons button:hover {
            transform: translateY(-2px);
        }

        /* ===== FEATURES ===== */
        .features,
        .why-choose-us {
            padding: 80px 70px;
        }

        .features {
            background: #ecfeff;
        }

        .section-title {
            text-align: center;
            margin-bottom: 45px;
            font-size: 34px;
            color: #0f3f24;
        }

        .features-grid,
        .choose-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
        }

        .feature-item,
        .choose-item {
            background: #fff;
            border-radius: 16px;
            padding: 26px;
            box-shadow: 0 10px 26px rgba(0, 0, 0, 0.06);
        }

        .item-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 14px;
            background: #ccfbf1;
        }

        .icon-delivery,
        .icon-support {
            color: #0f766e;
        }

        .icon-payment,
        .icon-pricing {
            color: #115e59;
        }

        .icon-quality,
        .icon-trust {
            color: #0f5132;
        }

        .feature-item h3,
        .choose-item h3 {
            font-size: 22px;
            margin-bottom: 10px;
            color: #115e59;
        }

        .feature-item p,
        .choose-item p {
            color: #4f5c53;
            line-height: 1.6;
            font-size: 15px;
        }

        /* ===== WHY CHOOSE US ===== */
        .why-choose-us {
            background: #f0fdfa;
        }

        /* ===== PRODUCTS ===== */
        .products {
            padding: 80px 70px;
        }

        .products h2 {
            text-align: center;
            margin-bottom: 55px;
            font-size: 34px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 35px;
        }

        .card {
            background: #fff;
            border-radius: 16px;
            padding: 22px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-10px);
        }

        .card img {
            width: 100%;
            height: 220px;
            object-fit: contain;
            background: #ecfeff;
            border-radius: 12px;
        }

        .card h3 {
            margin: 18px 0 10px;
            font-size: 20px;
        }

        .card p {
            font-size: 14px;
            color: #666;
        }

        .price {
            margin: 15px 0;
            font-size: 18px;
            font-weight: bold;
            color: #0f766e;
        }

        .card button {
            padding: 12px 28px;
            background: #0f766e;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
        }

        .card button:hover {
            background: #115e59;
        }

        /* ===== FOOTER ===== */
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

        /* ===== RESPONSIVE ===== */
        @media(max-width:992px) {
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .features-grid,
            .choose-grid {
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

            .features,
            .why-choose-us,
            .products,
            footer {
                padding: 60px 20px;
            }

            .features-grid,
            .choose-grid {
                grid-template-columns: 1fr;
            }

            .hero h1 {
                font-size: 42px;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
   <nav> 
        <h2>My Ecom</h2>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="contact.php">Contact</a></li>
            <?php if (isset($_SESSION['name']) && isset($_SESSION['user_email'])) { ?>
                <li style="color: white; font-weight: bold;">Welcome Back, <?php echo htmlspecialchars($_SESSION['name']); ?></li>
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

    <!-- HERO -->
    <section class="hero">
        <h1>Online Shopping Made Easy</h1>
        <p>Discover premium products, trusted quality and unbeatable prices — all in one place.</p>
        <div class="hero-buttons">
            <a href="products.php" class="primary">Shop Now</a>
            <button class="secondary">Explore</button>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="features">
        <h2 class="section-title">Features</h2>
        <div class="features-grid">
            <div class="feature-item">
                <span class="item-icon icon-delivery">&#128666;</span>
                <h3>Fast Delivery</h3>
                <p>Get your orders delivered quickly with our trusted and efficient shipping process.</p>
            </div>
            <div class="feature-item">
                <span class="item-icon icon-payment">&#128274;</span>
                <h3>Secure Payment</h3>
                <p>Shop with confidence using safe and reliable payment options for every transaction.</p>
            </div>
            <div class="feature-item">
                <span class="item-icon icon-quality">&#10004;</span>
                <h3>Quality Products</h3>
                <p>We offer carefully selected products that meet high quality and performance standards.</p>
            </div>
        </div>
    </section>

    <!-- WHY CHOOSE US -->
    <section class="why-choose-us">
        <h2 class="section-title">Why Choose Us</h2>
        <div class="choose-grid">
            <div class="choose-item">
                <span class="item-icon icon-support">&#128172;</span>
                <h3>Customer-First Support</h3>
                <p>Our support team is always ready to help you with orders, products, and any questions.</p>
            </div>
            <div class="choose-item">
                <span class="item-icon icon-pricing">&#128181;</span>
                <h3>Affordable Pricing</h3>
                <p>Enjoy competitive prices and great value without compromising on product quality.</p>
            </div>
            <div class="choose-item">
                <span class="item-icon icon-trust">&#11088;</span>
                <h3>Trusted by Shoppers</h3>
                <p>Thousands of customers rely on us for a smooth, transparent, and reliable shopping experience.</p>
            </div>
        </div>
    </section>
    <?php
    include '../admin/databaseconnection.php';
    $fetch_query = "SELECT * FROM products";
    $result = mysqli_query($conn, $fetch_query);
    $conn->close();
    ?>
    <!-- PRODUCTS -->
    <section class="products">
        <h2>Our Products</h2>

        <div class="product-grid">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="card">
                    <img src="../photos/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                    <h3><?php echo $row['name']; ?></h3>
                    <p><?php echo $row['description']; ?></p>
                    <div class="price">Rs.<?php echo $row['price']; ?></div>

                    <a href="product_details.php?id=<?php echo $row['id']; ?>"><button>Buy Now</button> </a>
                </div>
            <?php } ?>
        </div>
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