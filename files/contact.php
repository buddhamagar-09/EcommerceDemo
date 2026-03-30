<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
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

        .contact-section {
            max-width: 900px;
            margin: 70px auto;
            padding: 0 20px;
        }

        .contact-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
            padding: 34px;
        }

        .contact-card h1 {
            color: #14532d;
            margin-bottom: 12px;
            font-size: 34px;
        }

        .contact-card p {
            color: #4b5563;
            margin-bottom: 24px;
        }

        .contact-info {
            margin-bottom: 26px;
        }

        .contact-info div {
            margin-bottom: 8px;
            color: #111;
        }

        .contact-form {
            display: grid;
            gap: 14px;
        }

        .contact-form input,
        .contact-form textarea {
            width: 100%;
            border: 1px solid #d7e4da;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 15px;
            outline: none;
        }

        .contact-form textarea {
            resize: vertical;
            min-height: 120px;
        }

        .contact-form button {
            width: fit-content;
            border: none;
            border-radius: 999px;
            padding: 12px 24px;
            background: #1f7a3d;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
        }

        .contact-form button:hover {
            background: #14532d;
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

            .contact-card {
                padding: 24px;
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
            <?php if (isset($_SESSION['name']) && isset($_SESSION['user_email'])) { ?>
                <li style="color: white;">Welcome Back <?php echo htmlspecialchars($_SESSION['name']); ?></li>
                <a href="cart.php" style="color: white;"><li class="fa-solid fa-cart-shopping"></li></a>
                <li><a href="logout.php" style="color: white; background: black; border-radius: 20px; font-size: large; padding: 5px 15px;">Logout</a></li>
            <?php } else { ?>
                <li><a href="register.php">Register</a></li>
                <li><a href="login.php">Login</a></li>
            <?php } ?>
        </ul>
    </nav>

    <section class="contact-section">
        <div class="contact-card">
            <h1>Contact Us</h1>
            <p>Have any questions? Send us a message and we will get back to you soon.</p>

            <div class="contact-info">
                <div><strong>Email:</strong> info@gmail.com</div>
                <div><strong>Phone:</strong> 98XXXXXXXX</div>
                <div><strong>Address:</strong> Kathmandu, Nepal</div>
            </div>

            <form class="contact-form" action="#" method="post">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
                <textarea name="message" placeholder="Your Message" required></textarea>
                <button type="submit">Send Message</button>
            </form>
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
