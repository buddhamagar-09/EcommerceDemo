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
    }
}

$display_name = $_SESSION['user_name'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed</title>
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
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            color: #0f172a;
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
            flex-wrap: wrap;
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

        .page-center {
            flex: 1;
            width: 100%;
            padding: 48px 20px 56px;
            display: grid;
            place-items: center;
            background:
                radial-gradient(circle at 8% 12%, rgba(20, 184, 166, 0.2) 0 18%, transparent 22%),
                radial-gradient(circle at 92% 88%, rgba(15, 118, 110, 0.18) 0 16%, transparent 20%),
                linear-gradient(145deg, #ecfeff, #ccfbf1, #99f6e4);
        }

        .failure-card {
            width: min(380px, 100%);
            background: #ffffff;
            border-radius: 20px;
            padding: 46px 28px 40px;
            text-align: center;
            box-shadow: 0 18px 44px rgba(15, 23, 42, 0.16);
            border: 1px solid #d1fae5;
            position: relative;
            overflow: hidden;
        }

        .failure-card::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(to right, #115e59, #14b8a6);
        }

        .icon {
            width: 82px;
            height: 82px;
            margin: 4px auto 16px;
            border-radius: 50%;
            background: linear-gradient(145deg, #fee2e2, #fecaca);
            color: #dc2626;
            display: grid;
            place-items: center;
            font-size: 38px;
            font-weight: 700;
            box-shadow: 0 12px 24px rgba(220, 38, 38, 0.18);
        }

        h1 {
            font-size: 34px;
            margin-bottom: 10px;
            color: #115e59;
        }

        p {
            color: #475569;
            line-height: 1.65;
            font-size: 15px;
            margin-bottom: 20px;
        }

        .failure-note {
            background: #f0fdfa;
            border: 1px solid #99f6e4;
            color: #0f766e;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 14px;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-retry {
            background: #dc2626;
            color: #fff;
            box-shadow: 0 10px 24px rgba(220, 38, 38, 0.24);
        }

        .btn-retry:hover {
            background: #b91c1c;
        }

        .btn-home {
            background: linear-gradient(to right, #115e59, #0f766e);
            color: #fff;
            box-shadow: 0 10px 24px rgba(15, 118, 110, 0.24);
        }

        .btn-home:hover {
            background: linear-gradient(to right, #0f6a64, #0e8077);
        }

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

        footer a {
            color: #ccc;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        .copy {
            text-align: center;
            margin-top: 55px;
            font-size: 14px;
            color: #aaa;
        }

        @media (max-width: 992px) {
            .footer-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            nav {
                justify-content: center;
                gap: 20px;
                padding: 22px 20px;
            }

            nav ul {
                justify-content: center;
            }

            .page-center {
                padding: 32px 16px 40px;
            }

            .failure-card {
                padding: 34px 20px 30px;
            }

            h1 {
                font-size: 28px;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

            footer {
                padding: 48px 20px;
            }

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
            <?php if ($display_name) { ?>
                <li style="color: white; font-weight: bold;">Welcome Back, <?php echo htmlspecialchars($display_name); ?></li>
                <li>
                    <a href="cart.php" style="color: white;">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <?php if ($cart_count > 0) { echo '<sup style="font-size: 0.82em; font-weight: 700; margin-left: 2px;">' . $cart_count . '</sup>'; } ?>
                    </a>
                </li>
                <li><a href="logout.php" style="color: white; background: #0f172a; border-radius: 20px; font-size: large; padding: 5px 15px;">Logout</a></li>
            <?php } else { ?>
                <li><a href="register.php">Register</a></li>
                <li><a href="login.php">Login</a></li>
            <?php } ?>
        </ul>
    </nav>

    <div class="page-center">
        <div class="failure-card">
            <div class="icon">!</div>
            <h1>Payment Failed</h1>
            <p>Your payment could not be completed. Please try again or return to the home page.</p>
            <div class="failure-note">If money was deducted, it will be reversed automatically by your bank.</div>

            <div class="actions">
                <a class="btn btn-retry" href="checkout.php">Try Again</a>
                <a class="btn btn-home" href="index.php">Go Home</a>
            </div>
        </div>
    </div>

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

        <div class="copy">© 2026 My Ecom. All Rights Reserved.</div>
    </footer>
</body>

</html>
