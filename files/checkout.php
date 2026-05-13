<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
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
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
// fetch order details from cart table and calculate total price
$total_price = 0;
$shipping_fee = 200; // fixed shipping fee

$products_sql = "SELECT p.name,p.image, c.quantity, (p.price * c.quantity) AS total_price 
                 FROM cart c 
                 JOIN products p ON c.product_id = p.id 
                 WHERE c.user_id = $user_id";
$product_result = mysqli_query($conn, $products_sql);
$conn->close();
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

        /* IMPORTANT — keeps footer at bottom */
        body {
            background: #f0fdfa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            color: #0f172a;
        }

        /* ===== NAVBAR ===== */

        nav {
            background: linear-gradient(to right, #115e59, #0f766e);
            padding: 28px 70px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            box-shadow: 0 8px 24px rgba(15, 118, 110, 0.2);
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

        .logout_btn {
            background-color: orangered;
            color: white;
            padding: 8px 18px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 20px;
        }

        /* CHECKOUT CONTAINER */

        .checkout-container {
            width: min(1320px, 95%);
            margin: auto;
            margin-top: 42px;
            margin-bottom: 56px;
        }

        .checkout-heading {
            margin-bottom: 16px;
        }

        .checkout-heading h1 {
            font-size: 34px;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .checkout-heading p {
            color: #475569;
            font-size: 15px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 12px;
            color: #115e59;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            padding: 8px 12px;
            border: 1px solid #99f6e4;
            border-radius: 999px;
            background: #ecfeff;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .back-link:hover {
            background: #0f766e;
            color: #ffffff;
        }

        .checkout-grid {
            display: flex;
            width: 100%;
            gap: 18px;
            align-items: stretch;
        }

        /* SHIPPING FORM */

        .checkout-form {
            width: 56%;
            background: white;
            padding: 22px 24px;
            border-radius: 16px;
            border-top: 5px solid #14b8a6;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
        }

        .checkout-form h2 {
            margin-bottom: 14px;
            color: #0f172a;
        }

        .shipping-fields {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .field-block {
            display: flex;
            flex-direction: column;
        }

        .field-block.full {
            grid-column: 1 / -1;
        }

        .checkout-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 13px;
            color: #0f172a;
        }

        .checkout-form input,
        .checkout-form textarea {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 0;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-size: 14px;
            background: #fcfffe;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .checkout-form input:focus,
        .checkout-form textarea:focus {
            border-color: #0f766e;
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.15);
            outline: none;
        }

        /* ORDER SUMMARY */

        .order-summary {
            width: 44%;
            background: white;
            padding: 22px 24px;
            border-radius: 16px;
            border-top: 5px solid #0f766e;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
        }

        .order-summary h2 {
            margin-bottom: 14px;
            color: #0f172a;
        }

        .order-summary table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            border: 1px solid #dbe7e3;
            border-radius: 12px;
            overflow: hidden;
        }

        .order-summary th,
        .order-summary td {
            padding: 9px 8px;
            border-bottom: 1px solid #ddd;
            text-align: left;
            vertical-align: middle;
            font-size: 13px;
        }

        .order-summary th {
            background: #f0fdfa;
            color: #115e59;
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        .order-summary tr:last-child td {
            border-bottom: none;
        }

        .order-item-row:nth-child(even) {
            background: #fafefd;
        }

        .item-image {
            width: 52px;
            height: 52px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #d5e5e1;
        }

        .item-name {
            font-weight: 600;
            color: #0f172a;
        }

        .item-qty {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            padding: 4px 10px;
            border-radius: 999px;
            background: #e6fffa;
            color: #0f766e;
            font-weight: 700;
            font-size: 13px;
        }

        .item-total {
            font-weight: 700;
            color: #115e59;
        }

        .total {
            background: #ecfeff;
            border: 1px solid #99f6e4;
            border-radius: 12px;
            padding: 12px;
            font-size: 16px;
            font-weight: 600;
            line-height: 1.45;
            margin-bottom: 14px;
        }

        /* PAYMENT */

        .payment-method h3 {
            margin-bottom: 8px;
            color: #0f172a;
        }

        .payment-options {
            display: flex;
            gap: 10px;
            align-items: stretch;
        }

        .payment-method label {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 8px 10px;
            margin-bottom: 6px;
        }

        .cod-btn {
            flex: 1;
            border: 1px solid #0f766e;
            background: #0f766e;
            color: #fff;
            font-weight: 700;
            border-radius: 10px;
            padding: 10px 12px;
            cursor: pointer;
            margin-bottom: 0;
        }

        .cod-btn:hover {
            background: #115e59;
            border-color: #115e59;
        }

        .esewa-option {
            flex: 1;
            justify-content: center;
            margin-bottom: 0;
            cursor: pointer;
        }

        /* BUTTON */

        .place-btn {
            background: #0f766e;
            border: none;
            color: white;
            padding: 12px;
            width: 150px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .place-btn:hover {
            background: #115e59;
        }



        /* ===== FOOTER ===== */

        footer {
            background: #0f172a;
            color: #ccc;
            padding: 80px 70px;
            margin-top: auto;
            /* keeps footer bottom */
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

        @media(max-width:768px) {

            nav {
                justify-content: center;
                gap: 20px;
            }

            nav ul {
                flex-wrap: wrap;
                justify-content: center;
            }

            .checkout-grid {
                flex-direction: column;
            }

            .checkout-form,
            .order-summary {
                width: 100%;
            }

            .shipping-fields {
                grid-template-columns: 1fr;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }

        }

        @media(max-width:500px) {

            .footer-grid {
                grid-template-columns: 1fr;
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
                <li style="color: white;">Welcome Back <?php echo htmlspecialchars($_SESSION['user_name']); ?></li>
                <a href="cart.php" style="color: white; ">
                    <li class="fa-solid fa-cart-shopping"><?php if ($cart_count > 0) {
                        echo '<sup style="font-size: 0.82em; font-weight: 700; margin-left: 2px;">' . $cart_count . '</sup>';
                    } ?></li>
                </a>
                <li><a href="logout.php"
                        style="color: white; background: #0f172a; border-radius: 20px; font-size: large; padding: 5px 15px;">Logout</a>
                </li>
            <?php } else { ?>
                <li><a href="register.php">Register</a></li>
                <li><a href="login.php">Login</a></li>
            <?php } ?>
        </ul>
    </nav>

    <!-- CHECKOUT SECTION -->

    <div class="checkout-container">
        <div class="checkout-heading">
            <h1>Secure Checkout</h1>
            <p>Review your order and shipping details before placing your order.</p>
            <a class="back-link" href="cart.php"><i class="fa-solid fa-arrow-left"></i> Go Back</a>
        </div>

        <!-- SHIPPING DETAILS -->
        <form action="shipping.php" method="POST" class="checkout-grid">
            <div class="checkout-form">

                <h2>Shipping Details</h2>

                <div class="shipping-fields">
                    <div class="field-block">
                        <label for="user_name">Full Name</label>
                        <input id="user_name" name="user_name" type="text" placeholder="Enter receiver full name"
                            required>
                    </div>

                    <div class="field-block">
                        <label for="user_email">Email</label>
                        <input id="user_email" name="user_email" type="email" placeholder="name@example.com" required>
                    </div>

                    <div class="field-block">
                        <label for="user_phone">Phone</label>
                        <input id="user_phone" name="user_phone" type="text" placeholder="98XXXXXXXX" required>
                    </div>

                    <div class="field-block full">
                        <label for="user_address">Shipping Address</label>
                        <input id="user_address" name="user_address" type="text"
                            placeholder="Street, ward, city, nearby landmark" required>
                    </div>
                </div>

            </div>


            <!-- ORDER SUMMARY -->

            <div class="order-summary">

                <h2>Order Summary</h2>
                <table>

                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($product_result)) { ?>
                        <tr class="order-item-row">
                            <td><img class="item-image" src="../photos/<?php echo $row['image']; ?>"
                                    alt="<?php echo $row['name']; ?>"></td>
                            <td class="item-name"><?php echo $row['name']; ?></td>
                            <td><span class="item-qty"><?php echo $row['quantity']; ?></span></td>
                            <td class="item-total">Rs <?php echo $row['total_price']; ?></td>
                        </tr>
                        <?php
                        $total_price += $row['total_price'];
                    } ?>
                </table>
                <div class="total">
                    Shipping: Rs.200<br>
                    Grand Total : Rs <?php echo $total_price + $shipping_fee; ?>
                </div>
                <input type="hidden" name="total_price" value="<?php echo $total_price + $shipping_fee; ?> ">
                <?php
                $transaction_uuid = uniqid(); // generate unique transaction ID
                $total_amount = $total_price + $shipping_fee;
                ?>
                <input type="hidden" name="transaction_uuid" value="<?php echo $transaction_uuid; ?>">
                <input type="hidden" name="total_amount" value="<?php echo $total_amount; ?>">
                <div class="payment-method">

                    <h3>Payment Method</h3>

                    <div class="payment-options">
                        <button type="button" class="cod-btn" onclick="Cod_function('cod')">Cash on Delivery</button>

                        <label class="esewa-option">
                            <input height="50px" width="100px" type="image" src="../photos/esewa.png" alt="eSewa">
                        </label>
                    </div>

                </div>

                <br>

            </div>
        </form>

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
                    <li>Home</li>
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

    <script>

        function Cod_function(value) {

            let name = document.getElementById('user_name').value.trim();
            let email = document.getElementById('user_email').value.trim();
            let phone = document.getElementById('user_phone').value.trim();
            let address = document.getElementById('user_address').value.trim();


            if (name === '' || email === '' || phone === '' || address === '') {
                alert('Please fill in all shipping details before placing the order.');
                return;
            }
            if (value == 'cod') {
                let res = confirm("Are you sure you want to place the order with Cash on Delivery?");
                if (res == true) {
                    const checkoutForm = document.querySelector('.checkout-grid');
                    checkoutForm.action = 'cod_success.php';
                    checkoutForm.method = 'POST';
                    checkoutForm.submit();

                } else {
                    window.location.href = 'cod_failure.php';
                }
            }
        }
    </script>
</body>

</html>