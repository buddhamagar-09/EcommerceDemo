<?php
session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include '../admin/databaseconnection.php';
$fetch_result = false;
$has_items = false;
$subtotal = 0;
$cart_count = 0;
$added_to_cart = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity'] ?? 1);
    $user_id = $_SESSION['user_id'];

    if ($user_id) {
        $check_query = "Select * from Cart where user_id = $user_id and product_id = $product_id";
        $check_result = mysqli_query($conn, $check_query);
        if ($check_result->num_rows > 0) {
            $update_query = "Update cart set quantity = quantity + $quantity where user_id = $user_id and product_id = $product_id";
            $update_result = mysqli_query($conn, $update_query);
            $added_to_cart = true;
        } else {
            $price_query = "Select price from products where id = $product_id";
            $price_result = mysqli_query($conn, $price_query);
            $product = mysqli_fetch_assoc($price_result);
            $price = $product['price'];

            $insert_query = "Insert into cart (user_id,product_id,quantity,price) values($user_id,$product_id,$quantity,$price)";
            mysqli_query($conn, $insert_query);
            $added_to_cart = true;
        }
    }
}
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $count_query = "Select count(*) as count from cart where user_id = $user_id";
    $count_result = mysqli_query($conn, $count_query);
    if ($count_result) {
        $count_row = mysqli_fetch_assoc($count_result);
        $cart_count = (int) ($count_row['count']);
    }

    $fetch_query = "Select c.*,p.name,p.image from cart c 
                    inner join products p on c.product_id = p.id
                    where c.user_id = $user_id";
    $fetch_result = mysqli_query($conn, $fetch_query);
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Ecom | Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", sans-serif;
        }

        body {
            background: radial-gradient(circle at top right, #ccfbf1 0%, #f0fdfa 40%, #e6fffa 100%);
            color: #111;
            min-height: 100vh;
        }

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
            align-items: center;
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
        .logout-btn {
            color: white;
            background: #0f172a;
            border-radius: 20px;
            font-size: 16px;
            padding: 6px 14px;
        }

        .cart-section {
            padding: 70px;
            max-width: 1300px;
            margin: auto;
        }

        .cart-header {
            background: linear-gradient(130deg, #ffffff 0%, #ecfeff 100%);
            border: 1px solid #c7f3ec;
            border-radius: 20px;
            box-shadow: 0 14px 34px rgba(15, 118, 110, 0.12);
            padding: 26px;
            margin-bottom: 26px;
        }

        .cart-header-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .cart-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: #0f766e;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 8px 20px rgba(15, 118, 110, 0.24);
        }

        .cart-title {
            font-size: 36px;
            margin-bottom: 12px;
            color: #115e59;
        }

        .cart-subtitle {
            color: #4b5563;
            margin-top: 8px;
        }

        .success-note {
            margin-top: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 12px;
            background: #dcfce7;
            color: #166534;
            font-weight: 600;
            font-size: 14px;
        }

        .cart-layout {
            display: grid;
            grid-template-columns: minmax(0, 2fr) minmax(300px, 1fr);
            gap: 30px;
            align-items: start;
        }

        .cart-items {
            grid-column: 1;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid #d1f4ed;
        }

        .cart-head,
        .cart-row {
            display: grid;
            grid-template-columns: 2.2fr 1fr 1fr 0.8fr;
            gap: 18px;
            align-items: center;
            padding: 18px 22px;
        }

        .cart-head {
            background: #ccfbf1;
            font-weight: 700;
            color: #115e59;
            border-bottom: 1px solid #bfeee6;
        }

        .cart-row {
            border-bottom: 1px solid #d5f5ef;
            transition: background 0.2s ease;
        }

        .cart-row:hover {
            background: #f7fffd;
        }

        .cart-row:last-child {
            border-bottom: none;
        }

        .product-cell {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .product-cell img {
            width: 72px;
            height: 72px;
            object-fit: contain;
            background: #f7faf8;
            border-radius: 10px;
            border: 1px solid #ccefe8;
        }

        .product-name {
            font-weight: 600;
            margin-bottom: 4px;
        }

        .product-meta {
            font-size: 13px;
            color: #6b7280;
        }

        .price,
        .total {
            color: #0f766e;
            font-weight: 700;
        }

        .qty-box {
            display: inline-flex;
            align-items: center;
            border: 1px solid #b8e9e1;
            border-radius: 25px;
            overflow: hidden;
            background: #fff;
            padding: 2px 10px;
        }

        .qty-form {
            display: flex;
            align-items: center;
            margin: 0;
        }

        .qty-btn {
            width: 34px;
            height: 34px;
            border: none;
            background: #f7faf8;
            cursor: pointer;
            font-size: 18px;
            color: #115e59;
        }

        .qty-num {
            width: 70px;
            text-align: center;
            font-weight: 600;
            border: none;
            background: transparent;
            color: #115e59;
            font-size: 15px;
            outline: none;
            padding: 6px 0;
        }

        .qty-num:focus {
            box-shadow: inset 0 0 0 2px #99f6e4;
            border-radius: 18px;
        }

        .remove-form {
            margin-top: 6px;
        }

        .remove-link {
            color: #dc2626;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            border: none;
            background: transparent;
            cursor: pointer;
            padding: 0;
            line-height: 1.2;
        }

        .remove-link:hover {
            color: #b91c1c;
        }

        .summary {
            grid-column: 2;
            background: linear-gradient(170deg, #ffffff 0%, #f6fffd 100%);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 16px 35px rgba(15, 118, 110, 0.14);
            position: sticky;
            top: 20px;
            border: 1px solid #d2f2ec;
        }

        .summary h3 {
            font-size: 24px;
            margin-bottom: 18px;
            color: #115e59;
        }

        .summary-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            color: #4b5563;
        }

        .summary-total {
            margin-top: 18px;
            padding-top: 16px;
            border-top: 1px solid #e8efe9;
            font-size: 20px;
            font-weight: 700;
            color: #111;
            display: flex;
            justify-content: space-between;
        }

        .checkout-btn {
            margin-top: 20px;
            width: 100%;
            border: none;
            border-radius: 30px;
            background: #0f766e;
            color: #fff;
            font-weight: 600;
            font-size: 16px;
            padding: 14px;
            cursor: pointer;
        }

        .checkout-btn:hover {
            background: #115e59;
            box-shadow: 0 8px 20px rgba(17, 94, 89, 0.24);
        }

        .continue {
            margin-top: 12px;
            width: 100%;
            border: 1px solid #b8e9e1;
            border-radius: 30px;
            background: #fff;
            color: #115e59;
            font-weight: 600;
            font-size: 15px;
            padding: 12px;
            cursor: pointer;
        }

        .continue:hover {
            background: #ecfeff;
        }

        .empty-cart {
            display: block;
            background: #fff;
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
        }

        .empty-cart h3 {
            font-size: 30px;
            color: #115e59;
            margin-bottom: 10px;
        }

        .empty-cart p {
            color: #6b7280;
            margin-bottom: 20px;
        }

        .empty-cart-cta {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 22px;
            border-radius: 999px;
            background: #0f766e;
            color: #fff;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            transition: background 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .empty-cart-cta:hover {
            background: #115e59;
            transform: translateY(-1px);
            box-shadow: 0 8px 16px rgba(17, 94, 89, 0.25);
        }

        footer {
            background: #0f172a;
            color: #ccc;
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
            color: #fff;
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

        @media(max-width:900px) {
            .cart-layout {
                grid-template-columns: minmax(0, 2fr) minmax(260px, 1fr);
                gap: 16px;
            }

            .summary {
                grid-column: 2;
                position: static;
            }
        }

        @media(max-width:760px) {
            nav {
                padding: 24px 18px;
                justify-content: center;
                gap: 16px;
            }

            nav ul {
                flex-wrap: wrap;
                justify-content: center;
                gap: 16px;
            }

            .cart-section {
                padding: 30px 12px;
            }

            .cart-header {
                padding: 18px;
            }

            .cart-head {
                display: none;
            }

            .cart-row {
                grid-template-columns: 1fr;
                gap: 12px;
                padding: 18px;
            }

            .product-cell {
                align-items: flex-start;
            }

            footer {
                padding: 55px 20px;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 28px;
            }
        }
    </style>
</head>

<body>
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

    <section class="cart-section">
        <div class="cart-header">
            <div class="cart-header-top">
                <div>
                    <h1 class="cart-title">Shopping Cart</h1>
                    <p class="cart-subtitle">Review your selected items before checkout.</p>
                </div>
                <span class="cart-badge"><i class="fa-solid fa-bag-shopping"></i> <?php echo (int) $cart_count; ?> item(s)</span>
            </div>
            <?php if ($added_to_cart) { ?>
                <div class="success-note"><i class="fa-solid fa-circle-check"></i> Item added to your cart successfully.</div>
            <?php } ?>
        </div>


        <?php if ($has_items = mysqli_num_rows($fetch_result) > 0) { ?>
            <div class="cart-layout">
                <div class="cart-items">
                    <div class="cart-head">
                        <div>Product</div>
                        <div>Price</div>
                        <div>Quantity</div>
                        <div>Total</div>
                    </div>
                    <?php while ($cartitems = mysqli_fetch_assoc($fetch_result)) { ?>
                        <?php if (!empty($cartitems)) {
                            $row_total = $cartitems['price'] * $cartitems['quantity'];
                            $subtotal += $row_total;
                            ?>
                            <div class="cart-row">
                                <div class="product-cell">
                                    <img src="../photos/<?php echo $cartitems['image']; ?>" alt="Product">
                                    <div>
                                        <div class="product-name"><?php echo $cartitems['name']; ?></div>
                                    </div>
                                </div>
                                <div class="price">Rs. <?php echo $cartitems['price']; ?></div>
                                <div>
                                    <div class="qty-box">
                                        <form class="qty-form" action="../admin/update_cart.php" method="post">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="cart_id" value="<?php echo $cartitems['id']; ?>">
                                           
                                            <input type="number" class="qty-num" name="quantity" value="<?php echo (int) $cartitems['quantity']; ?>" min="1" onchange="this.form.submit()">
                                          
                                        </form>
                                    </div>
                                </div>
                                <div>
                                    <div class="total">RS. <?php echo number_format($row_total, 2); ?></div>
                                <form class="remove-form" action="../admin/update_cart.php" method="post">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="cart_id" value="<?php echo $cartitems['id']; ?>">
                                        <button class="remove-link" type="submit">Remove</button>
                                   </form>
                                </div>
                            </div>
                        <?php }
                    } ?>

                </div>


                <aside class="summary">
                    <h3>Order Summary</h3>
                    <div class="summary-line">
                        <span>Subtotal</span>
                        <span>Rs. <?php echo number_format($subtotal, 2); ?></span>
                    </div>

                    <button class="checkout-btn" type="button" onclick="window.location.href='checkout.php'">Proceed to Checkout</button>
                    <button class="continue" type="button" onclick="window.location.href='products.php'">Continue
                        Shopping</button>
                </aside>
            </div>
        <?php } else { ?>

            <!--
            Show this block when cart is empty.
            Example: add style display:block to .empty-cart and hide .cart-layout
        -->
            <div class="empty-cart">
                <h3>Your Cart Is Empty</h3>
                <p>Add products from the shop and they will appear here.</p>
                <a class="empty-cart-cta" href="products.php">Browse Products</a>
            </div>
        <?php } ?>
    </section>

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
                <h4>Company</h4>
                <ul>
                    <li>About Us</li>
                    <li>Our Team</li>
                    <li>Careers</li>
                </ul>
            </div>
            <div>
                <h4>Contact</h4>
                <ul>
                    <li>support@myecom.com</li>
                    <li>+1 000 123 4567</li>
                    <li>New York, USA</li>
                </ul>
            </div>
        </div>
        <p class="copy">Copyright 2026 My Ecom. All rights reserved.</p>
    </footer>
</body>

</html>