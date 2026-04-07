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

// fetch order details from cart table and calculate total price
$total_price = 0;
$shipping_fee = 200; // fixed shipping fee

$products_sql = "SELECT p.name, c.quantity, (p.price * c.quantity) AS total_price 
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
            width: min(1100px, 92%);
            margin: auto;
            margin-top: 70px;
            margin-bottom: 90px;
        }

        .checkout-heading {
            margin-bottom: 22px;
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
            gap: 24px;
            align-items: flex-start;
        }

        /* SHIPPING FORM */

        .checkout-form {
            width: 50%;
            background: white;
            padding: 30px;
            border-radius: 16px;
            border-top: 5px solid #14b8a6;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
        }

        .checkout-form h2 {
            margin-bottom: 20px;
            color: #0f172a;
        }

        .checkout-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .checkout-form input,
        .checkout-form textarea {
            width: 100%;
            padding: 12px 14px;
            margin-bottom: 15px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-size: 15px;
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
            width: 50%;
            background: white;
            padding: 30px;
            border-radius: 16px;
            border-top: 5px solid #0f766e;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
        }

        .order-summary h2 {
            margin-bottom: 20px;
            color: #0f172a;
        }

        .order-summary table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .order-summary th,
        .order-summary td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        .order-summary th {
            background: #f0fdfa;
            color: #115e59;
            font-weight: 700;
        }

        .total {
            background: #ecfeff;
            border: 1px solid #99f6e4;
            border-radius: 12px;
            padding: 14px;
            font-size: 18px;
            font-weight: 600;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        /* PAYMENT */

        .payment-method h3 {
            margin-bottom: 10px;
            color: #0f172a;
        }

        .payment-method label {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 12px;
            margin-bottom: 8px;
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
        <h2>My Ecom</h2>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="contact.php">Contact</a></li>
            <?php if (isset($_SESSION['name']) && isset($_SESSION['user_email'])) { ?>
                <li style="color: white;">Welcome Back <?php echo htmlspecialchars($_SESSION['name']); ?></li>
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
        <form action="" method="" class="checkout-grid">
            <div class="checkout-form">

                <h2>Shipping Details</h2>

                <label>Full Name</label>
                <input name="user_name" type="text" value="<?php echo $row['name']; ?>" required>

                <label>Email</label>
                <input name="user_email" type="email" value="<?php echo $row['email']; ?>" required>

                <label>Phone</label>
                <input name="user_phone" type="text" value="<?php echo $row['phone']; ?>" required>

                <label>Address</label>
                <input name="user_address" type="text" value="<?php echo $row['address']; ?>" required>

            </div>


            <!-- ORDER SUMMARY -->

            <div class="order-summary">

                <h2>Order Summary</h2>
                <table>

                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($product_result)) { ?>
                        <tr>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td> Rs <?php echo $row['total_price']; ?></td>
                        </tr>
                    <?php
                        $total_price += $row['total_price'];
                    } ?>
                </table>
                <div class="total">
                    Shipping: Rs.200<br>
                    Grand Total : Rs <?php echo $total_price + $shipping_fee; ?>
                </div>

                <div class="payment-method">

                    <h3>Payment Method</h3>

                    <label>
                        <input type="radio" name="payment"> COD
                    </label>

                    <label>
                        <input height="50px" width="100px" type="image" src="../photos/esewa.png" alt="eSewa">
                    </label>

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

</body>

</html>