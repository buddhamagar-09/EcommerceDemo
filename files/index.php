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
        background: #F8FAFC;
        color: #0F172A;
    }

    /* ===== NAVBAR ===== */
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

    /* =========================
   HERO
========================= */

.hero {
    min-height: 620px;
    background: #FFFFFF;
    color: #0F172A;
    padding: 90px 8%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
}

/* Content */

.hero-content {
    max-width: 720px;
    position: relative;
    z-index: 2;
}

.hero-label {
    display: inline-block;
    color: #2563EB;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 2px;
    margin-bottom: 22px;
}

.hero h1 {
    font-size: clamp(48px, 7vw, 82px);
    line-height: 0.98;
    font-weight: 750;
    letter-spacing: -2px;
    color: #0F172A;
    margin-bottom: 25px;
}

.hero p {
    max-width: 560px;
    color: #64748B;
    font-size: 17px;
    line-height: 1.7;
    margin-bottom: 35px;
}

/* Buttons */

.hero-buttons {
    display: flex;
    align-items: center;
    gap: 12px;
}

.hero-buttons a,
.hero-buttons button {
    font-family: inherit;
    font-size: 14px;
    font-weight: 600;
    padding: 13px 24px;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.25s ease;
    text-decoration: none;
}

/* Primary */

.hero-buttons .primary {
    background: #2563EB;
    color: #FFFFFF;
    border: 1px solid #2563EB;
}

.hero-buttons .primary:hover {
    background: #1D4ED8;
    border-color: #1D4ED8;
}

/* Secondary */

.hero-buttons .secondary {
    background: #FFFFFF;
    color: #1E293B;
    border: 1px solid #CBD5E1;
}

.hero-buttons .secondary:hover {
    background: #F8FAFC;
    color: #2563EB;
    border-color: #2563EB;
}

/* Right-side detail */

.hero-side {
    display: flex;
    flex-direction: column;
    gap: 18px;
    padding-right: 20px;
    position: relative;
    z-index: 2;
}

.hero-side span {
    color: #64748B;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 2px;
    writing-mode: vertical-rl;
}

/* Simple decorative line */

.hero::after {
    content: "";
    position: absolute;
    right: 8%;
    top: 50%;
    width: 180px;
    height: 1px;
    background: #E2E8F0;
    transform: rotate(90deg);
}

/* =========================
   RESPONSIVE
========================= */

@media (max-width: 900px) {

    .hero {
        min-height: 560px;
        padding: 80px 6%;
    }

    .hero-side,
    .hero::after {
        display: none;
    }

    .hero-content {
        max-width: 700px;
    }
}

@media (max-width: 600px) {

    .hero {
        min-height: 520px;
        padding: 70px 20px;
    }

    .hero-label {
        font-size: 11px;
        margin-bottom: 18px;
    }

    .hero h1 {
        font-size: 46px;
        letter-spacing: -1.5px;
    }

    .hero p {
        font-size: 15px;
        margin-bottom: 28px;
    }

    .hero-buttons {
        width: 100%;
    }

    .hero-buttons a,
    .hero-buttons button {
        padding: 12px 20px;
    }
}

  
   /* =========================
   FEATURES
========================= */

.features {
    background: #FFFFFF;
    padding: 90px 8%;
    border-top: 1px solid #E2E8F0;
}

.features-header {
    max-width: 650px;
    margin-bottom: 55px;
}

.features-label {
    display: inline-block;
    margin-bottom: 14px;
    color: #2563EB;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1.5px;
}

.section-title {
    color: #0F172A;
    font-size: clamp(32px, 4vw, 46px);
    font-weight: 700;
    line-height: 1.15;
    margin-bottom: 16px;
}

.features-subtitle {
    color: #64748B;
    font-size: 16px;
    line-height: 1.7;
    max-width: 580px;
}

/* Grid */

.features-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    border-top: 1px solid #E2E8F0;
    border-bottom: 1px solid #E2E8F0;
}

/* Feature */

.feature-item {
    display: flex;
    gap: 22px;
    padding: 35px 30px;
    min-height: 210px;
    border-right: 1px solid #E2E8F0;
    transition: background 0.25s ease;
}

.feature-item:first-child {
    padding-left: 0;
}

.feature-item:last-child {
    border-right: none;
    padding-right: 0;
}

.feature-item:hover {
    background: #F8FAFC;
}

/* Number */

.feature-number {
    color: #2563EB;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 1px;
    padding-top: 3px;
    min-width: 28px;
}

/* Content */

.feature-content h3 {
    color: #0F172A;
    font-size: 19px;
    font-weight: 650;
    margin-bottom: 12px;
}

.feature-content p {
    color: #64748B;
    font-size: 14px;
    line-height: 1.7;
    max-width: 260px;
}

/* =========================
   RESPONSIVE
========================= */

@media (max-width: 900px) {

    .features {
        padding: 70px 6%;
    }

    .features-grid {
        grid-template-columns: 1fr;
    }

    .feature-item,
    .feature-item:first-child,
    .feature-item:last-child {
        padding: 28px 0;
        border-right: none;
        border-bottom: 1px solid #E2E8F0;
    }

    .feature-item:last-child {
        border-bottom: none;
    }

    .feature-content p {
        max-width: 500px;
    }
}

@media (max-width: 600px) {

    .features {
        padding: 60px 20px;
    }

    .features-header {
        margin-bottom: 35px;
    }

    .section-title {
        font-size: 32px;
    }

    .features-subtitle {
        font-size: 14px;
    }

    .feature-item {
        gap: 15px;
    }

    .feature-content h3 {
        font-size: 17px;
    }
}
 /* =========================
   WHY CHOOSE US
========================= */

.why-choose-us {
    background: #FFFFFF;
    color: #0F172A;
    padding: 90px 8%;
    border-top: 1px solid #E2E8F0;
}

.why-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 50px;
    margin-bottom: 60px;
}

.why-label {
    display: block;
    color: #2563EB;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1.5px;
    margin-bottom: 14px;
}

.why-header .section-title {
    color: #0F172A;
    font-size: clamp(32px, 4vw, 46px);
    font-weight: 700;
    line-height: 1.15;
    margin: 0;
}

.why-intro {
    max-width: 440px;
    color: #64748B;
    font-size: 15px;
    line-height: 1.7;
    margin-bottom: 2px;
}

/* =========================
   GRID
========================= */

.choose-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    border-top: 1px solid #E2E8F0;
    border-bottom: 1px solid #E2E8F0;
}

/* =========================
   ITEMS
========================= */

.choose-item {
    display: flex;
    gap: 25px;
    padding: 35px 30px 30px;
    min-height: 210px;
    border-right: 1px solid #E2E8F0;
    transition: background 0.25s ease;
}

.choose-item:first-child {
    padding-left: 0;
}

.choose-item:last-child {
    border-right: none;
    padding-right: 0;
}

.choose-item:hover {
    background: #F8FAFC;
}

/* =========================
   NUMBER
========================= */

.choose-number {
    color: #2563EB;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 1px;
    min-width: 28px;
    padding-top: 3px;
}

/* =========================
   CONTENT
========================= */

.choose-item h3 {
    color: #0F172A;
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 12px;
}

.choose-item p {
    color: #64748B;
    font-size: 14px;
    line-height: 1.7;
    max-width: 280px;
}

/* =========================
   RESPONSIVE
========================= */

@media (max-width: 900px) {

    .why-choose-us {
        padding: 70px 6%;
    }

    .why-header {
        display: block;
        margin-bottom: 40px;
    }

    .why-intro {
        margin-top: 18px;
        max-width: 600px;
    }

    .choose-grid {
        grid-template-columns: 1fr;
    }

    .choose-item,
    .choose-item:first-child,
    .choose-item:last-child {
        padding: 28px 0;
        border-right: none;
        border-bottom: 1px solid #E2E8F0;
    }

    .choose-item:last-child {
        border-bottom: none;
    }

    .choose-item p {
        max-width: 550px;
    }
}

@media (max-width: 600px) {

    .why-choose-us {
        padding: 60px 20px;
    }

    .why-header .section-title {
        font-size: 32px;
    }

    .why-intro {
        font-size: 14px;
    }

    .choose-item {
        gap: 16px;
    }

    .choose-item h3 {
        font-size: 17px;
    }

    .choose-item p {
        font-size: 13px;
    }
}
  
    /* ===== PRODUCTS ===== */
    .products {
        padding: 80px 70px;
        background: #FFFFFF;
    }

    .products h2 {
        text-align: center;
        margin-bottom: 35px;
        font-size: 34px;
        color: #1E293B;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 28px;
    }

    .card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.06);
        transition: 0.3s ease;
    }

    .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.12);
    }

    .card img {
        width: 100%;
        height: 220px;
        object-fit: contain;
        background: #F8FAFC;
        border-radius: 8px;
    }

    .card h3 {
        margin: 18px 0 10px;
        font-size: 20px;
        color: #1E293B;
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
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: 0.2s ease;
    }

    .card-btn:hover {
        transform: translateY(-2px);
    }

    .add-cart-btn {
        background: #2563EB;
        color: #FFFFFF;
    }

    .add-cart-btn:hover {
        background: #1D4ED8;
    }

    .details-btn {
        background: #FFFFFF;
        color: #2563EB;
        border: 1px solid #BFDBFE;
    }

    .details-btn:hover {
        background: #EFF6FF;
        border-color: #2563EB;
    }

    /* ===== FOOTER ===== */
    footer {
        background: #ffffff;
        color: #070707;
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
        color: #030303;
        margin-bottom: 20px;
    }

    footer ul {
        list-style: none;
    }

    footer ul li {
        margin-bottom: 12px;
        font-size: 14px;
    }

    footer ul li a {
        color: #0c0c0c;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    footer ul li a:hover {
        color: #171717;
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

        .features-grid,
        .choose-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width:600px) {
        nav {
            padding: 20px;
            justify-content: center;
            gap: 20px;
        }

        nav ul {
            flex-wrap: wrap;
            justify-content: center;
            gap: 16px;
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

        .nav-search {
            width: 100%;
            max-width: none;
            margin: 0;
        }

        .nav-search input {
            flex: 1;
        }

        .hero {
            padding: 90px 20px;
        }

        .hero h1 {
            font-size: 42px;
        }

        .hero-buttons button,
        .hero-buttons .primary {
            margin: 8px 4px;
        }

        .footer-grid {
            grid-template-columns: 1fr;
            gap: 30px;
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
                <a href="cart.php" style="color: black; "><li class="fa-solid fa-cart-shopping"><?php if ($cart_count > 0) {
                    echo '<sup style="font-size: 0.82em; font-weight: 700; margin-left: 2px;">' . $cart_count . '</sup>';
                    } ?></li></a>
                <li><a href="logout.php"
                        style="color: white; background: #2563EB; border-radius: 10px; font-size: large; padding: 5px 15px;">Logout</a>
                </li>
            <?php } else { ?>
                <li><a href="register.php">Register</a></li>
                <li><a href="login.php">Login</a></li>
            <?php } ?>
        </ul>
    </nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-content">

        <span class="hero-label">SHOP WITH CONFIDENCE</span>

        <h1>Online Shopping<br>Made Easy.</h1>

        <p>
            Discover quality products, great prices, and a shopping
            experience designed around you.
        </p>

        <div class="hero-buttons">
            <a href="products.php" class="primary">Shop Now</a>
            <button class="secondary">Explore</button>
        </div>

    </div>

    <div class="hero-side">
        <span>QUALITY</span>
        <span>STYLE</span>
        <span>VALUE</span>
    </div>
</section>

<!-- FEATURES -->
<section class="features">
    <div class="features-header">
        <span class="features-label">WHY SHOP WITH US</span>
        <h2 class="section-title">Shopping made simple.</h2>
        <p class="features-subtitle">
            From carefully selected products to reliable delivery,
            we make every part of your shopping experience easy.
        </p>
    </div>

    <div class="features-grid">

        <div class="feature-item">
            <div class="feature-number">01</div>
            <div class="feature-content">
                <h3>Fast Delivery</h3>
                <p>
                    Quick and dependable delivery, so your order
                    reaches you without unnecessary delays.
                </p>
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-number">02</div>
            <div class="feature-content">
                <h3>Secure Payment</h3>
                <p>
                    Your transactions are handled through secure
                    and reliable payment methods.
                </p>
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-number">03</div>
            <div class="feature-content">
                <h3>Quality Products</h3>
                <p>
                    Carefully selected products with quality you
                    can rely on every time you shop.
                </p>
            </div>
        </div>

    </div>
</section>

<!-- WHY CHOOSE US -->
<section class="why-choose-us">

    <div class="why-header">
        <span class="why-label">THE DIFFERENCE</span>

        <h2 class="section-title">Why choose us?</h2>

        <p class="why-intro">
            We focus on giving you a straightforward shopping experience,
            from discovering products to receiving your order.
        </p>
    </div>

    <div class="choose-grid">

        <div class="choose-item">
            <span class="choose-number">01</span>

            <div>
                <h3>Customer-First Support</h3>
                <p>
                    Our support team is always ready to help with orders,
                    products, and any questions you may have.
                </p>
            </div>
        </div>

        <div class="choose-item">
            <span class="choose-number">02</span>

            <div>
                <h3>Affordable Pricing</h3>
                <p>
                    Enjoy competitive prices and great value without
                    compromising on the quality of our products.
                </p>
            </div>
        </div>

        <div class="choose-item">
            <span class="choose-number">03</span>

            <div>
                <h3>Trusted by Shoppers</h3>
                <p>
                    Customers choose us for a smooth, transparent,
                    and reliable shopping experience.
                </p>
            </div>
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

        <div>
            <form class="nav-search" action="products.php" method="get">
            <input type="text" name="searchproduct" placeholder="Search products" value="<?php echo htmlspecialchars($_GET['searchproduct'] ?? ''); ?>">
            <button type="submit" name="search" value="Search"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
        </form>
        </div>

        <div class="product-grid">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="card">
                    <img src="../photos/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                    <h3><?php echo $row['name']; ?></h3>
                    <p><?php echo $row['description']; ?></p>
                    <div class="price">Rs.<?php echo $row['price']; ?></div>

                    <div class="card-actions">
                        <form action="cart.php" method="post">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="card-btn add-cart-btn">Add To Cart</button>
                        </form>
                        <a href="product_details.php?id=<?php echo $row['id']; ?>">
                            <button type="button" class="card-btn details-btn">View Details</button>
                        </a>
                    </div>
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