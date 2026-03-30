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
$query = "Select * from users where id = $user_id ";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", sans-serif;
        }

        body {
            background: #eef7f1;
            color: #111;
        }

        nav {
            background: linear-gradient(to right, #14532d, #1f7a3d);
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

        .checkout-section {
            max-width: 1200px;
            margin: 70px auto;
            padding: 0 20px;
        }

        .checkout-title {
            color: #14532d;
            font-size: 36px;
            margin-bottom: 10px;
        }

        .checkout-subtitle {
            color: #4b5563;
            margin-bottom: 28px;
        }

        .checkout-layout {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }

        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
            padding: 26px;
        }

        .card h3 {
            color: #14532d;
            margin-bottom: 16px;
            font-size: 24px;
        }

        .checkout-form {
            display: grid;
            gap: 14px;
        }

        .checkout-form input,
        .checkout-form select,
        .checkout-form textarea {
            width: 100%;
            border: 1px solid #d7e4da;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 15px;
            outline: none;
        }

        .checkout-form textarea {
            resize: vertical;
            min-height: 100px;
        }

        .row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .summary-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            color: #4b5563;
        }

        .summary-total {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #e5ece7;
            font-weight: 700;
            color: #111;
            display: flex;
            justify-content: space-between;
        }

        .place-order-btn {
            margin-top: 18px;
            width: 100%;
            border: none;
            border-radius: 999px;
            padding: 13px 20px;
            background: #1f7a3d;
            color: #fff;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
        }

        .place-order-btn:hover {
            background: #14532d;
        }

        .note {
            margin-top: 10px;
            font-size: 13px;
            color: #6b7280;
        }

        footer {
            background: #101010;
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

        footer ul li a {
            color: #ccc;
            text-decoration: none;
        }

        footer ul li a:hover {
            color: #fff;
        }

        .copy {
            text-align: center;
            margin-top: 55px;
            font-size: 14px;
            color: #aaa;
        }

        @media(max-width: 900px) {
            .checkout-layout {
                grid-template-columns: 1fr;
            }
        }

        @media(max-width: 768px) {
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

            .row-2 {
                grid-template-columns: 1fr;
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
        <h2>My Ecom</h2>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="contact.php">Contact</a></li>
            <?php if (isset($_SESSION['user_id'])) { ?>
                <li style="color: white;">Welcome Back <?php echo htmlspecialchars($_SESSION['name'] ?? ''); ?></li>
                <a href="cart.php" style="color: white; "><li class="fa-solid fa-cart-shopping"><?php if ($cart_count > 0) {
                    echo '<sup style="font-size: 0.82em; font-weight: 700; margin-left: 2px;">' . $cart_count . '</sup>';
                    } ?></li></a>
                <li><a href="logout.php"
                        style="color: white; background: black; border-radius: 20px; font-size: large; padding: 5px 15px;">Logout</a>
                </li>
            <?php } else { ?>
                <li><a href="register.php">Register</a></li>
                <li><a href="login.php">Login</a></li>
            <?php } ?>
        </ul>
    </nav>

    <section class="checkout-section">
        <h1 class="checkout-title">Checkout</h1>
        <p class="checkout-subtitle">Complete your details to place your order.</p>

        <div class="checkout-layout">
            <div class="card">
                <h3>Billing Details</h3>
                <form class="checkout-form" action="#" method="post">
                    <input type="text" name="full_name" value="<?php echo $row['name'] ?? ''; ?>"
                        placeholder="Full Name" required>
                    <div class="row-2">
                        <input type="email" name="email" placeholder="Email Address"
                            value="<?php echo $row['email'] ?? ''; ?>" required>
                        <input type="tel" name="phone" placeholder="Phone Number"
                            value="<?php echo $row['phone'] ?? ''; ?>" required>
                    </div>
                    <input type="text" name="address" placeholder="Street Address"
                        value="<?php echo $row['address'] ?? ''; ?>" required>
                    <div class="row-2">
                        <input type="text" name="city" placeholder="City" value="<?php echo $row['city'] ?? ''; ?>"
                            required>
                    </div>
                    <select name="payment_method" required>
                        <option value="">Select Payment Method</option>
                        <option value="cod">Cash on Delivery</option>
                        <option value="esewa">eSewa</option>
                        <option value="khalti">Khalti</option>
                    </select>

                </form>
            </div>

            <aside class="card">
                <h3>Order Summary</h3>
                <div class="summary-line">
                    <span>Subtotal</span>
                    <span>Rs. 0.00</span>
                </div>
                <div class="summary-line">
                    <span>Shipping</span>
                    <span>Rs. 0.00</span>
                </div>
                <div class="summary-total">
                    <span>Total</span>
                    <span>Rs. 0.00</span>
                </div>
                <button type="button" class="place-order-btn">Place Order</button>
                <p class="note">Frontend only page: button is UI-only for now.</p>
            </aside>
        </div>
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