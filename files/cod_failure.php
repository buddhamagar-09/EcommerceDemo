<?php
session_start();
include '../admin/databaseconnection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$display_name = $_SESSION['user_name'] ?? ($_SESSION['name'] ?? null);
$failure_reason = 'Unable to process order';

$cart_count = 0;
$count_query = "Select count(*) as count from cart where user_id = $user_id";
$count_result = mysqli_query($conn, $count_query);
if ($count_result) {
    $count_row = mysqli_fetch_assoc($count_result);
    $cart_count = (int) ($count_row['count']);
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COD Order Failed</title>
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
        display: flex;
        flex-direction: column;
        min-height: 100vh;
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

    .page-center {
        flex: 1;
        width: 100%;
        padding: 48px 20px 56px;
        display: grid;
        place-items: center;
        background: #EFF6FF;
    }

    .failure-card {
        width: min(450px, 100%);
        background: #FFFFFF;
        border-radius: 20px;
        padding: 46px 28px 40px;
        text-align: center;
        box-shadow: 0 18px 44px rgba(15, 23, 42, 0.16);
        border: 1px solid #E2E8F0;
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
        background: #2563EB;
    }

    .icon {
        width: 82px;
        height: 82px;
        margin: 4px auto 16px;
        border-radius: 50%;
        background: #EFF6FF;
        color: #2563EB;
        display: grid;
        place-items: center;
        font-size: 38px;
        font-weight: 700;
        box-shadow: 0 12px 24px rgba(37, 99, 235, 0.18);
    }

    h1 {
        font-size: 34px;
        margin-bottom: 10px;
        color: #1E293B;
    }

    p {
        color: #64748B;
        line-height: 1.65;
        font-size: 15px;
        margin-bottom: 16px;
    }

    .error-note {
        background: #EFF6FF;
        border: 1px solid #E2E8F0;
        color: #1D4ED8;
        padding: 10px 12px;
        border-radius: 10px;
        font-size: 13px;
        margin-bottom: 18px;
    }

    .error-details-box {
        text-align: left;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 14px 14px 12px;
        margin-bottom: 20px;
    }

    .error-details-box h2 {
        font-size: 16px;
        color: #1E293B;
        margin-bottom: 8px;
    }

    .error-details-box ul {
        margin-left: 18px;
        color: #64748B;
        font-size: 14px;
        line-height: 1.6;
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
        background: #2563EB;
        color: #fff;
        box-shadow: 0 10px 24px rgba(37, 99, 235, 0.24);
    }

    .btn-retry:hover {
        background: #1D4ED8;
    }

    .btn-home {
        background: #1E293B;
        color: #fff;
        box-shadow: 0 10px 24px rgba(30, 41, 59, 0.25);
    }

    .btn-home:hover {
        background: #0F172A;
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

        .error-details-box ul {
            font-size: 13px;
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
                <li style="color: black; font-weight: bold;">Welcome Back,
                    <?php echo ($display_name); ?>
                </li>
                <li>
                    <a href="cart.php" style="color: black;">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <?php if ($cart_count > 0) {
                            echo '<sup style="font-size: 0.82em; font-weight: 700; margin-left: 2px;">' . $cart_count . '</sup>';
                        } ?>
                    </a>
                </li>
                <li><a href="logout.php"
                        style="color: white; background: #2563EB; border-radius: 10px; font-size: large; padding: 5px 15px;">Logout</a>
                </li>
            <?php } else { ?>
                <li><a href="register.php">Register</a></li>
                <li><a href="login.php">Login</a></li>
            <?php } ?>
        </ul>
    </nav>

    <div class="page-center">
        <div class="failure-card">
            <div class="icon">✕</div>
            <h1>Order Failed</h1>
            <p>Unfortunately, your Cash on Delivery order could not be processed at this time.</p>
            <div class="error-note">
                <?php echo htmlspecialchars($failure_reason); ?>
            </div>

            <div class="error-details-box">
                <h2>What You Can Do</h2>
                <ul>
                    <li>Verify your delivery address and contact information.</li>
                    <li>Ensure your phone number is correct for delivery coordination.</li>
                    <li>Try placing the order again with updated information.</li>
                    <li>Contact support if the problem persists.</li>
                </ul>
            </div>

            <div class="actions">
                <a class="btn btn-retry" href="checkout.php">Retry Order</a>
                <a class="btn btn-home" href="index.php">Back To Home</a>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer-grid">
            <div>
                <h4>About Us</h4>
                <ul>
                    <li><a href="#">Company</a></li>
                    <li><a href="#">Mission</a></li>
                    <li><a href="#">Vision</a></li>
                </ul>
            </div>
            <div>
                <h4>Support</h4>
                <ul>
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Help Center</a></li>
                </ul>
            </div>
            <div>
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="products.php">Shop</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Careers</a></li>
                </ul>
            </div>
            <div>
                <h4>Legal</h4>
                <ul>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms & Conditions</a></li>
                    <li><a href="#">Return Policy</a></li>
                </ul>
            </div>
        </div>
        <div class="copy">
            <p>&copy; 2026 Sexy Wears. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>
