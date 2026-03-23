<?php 
session_start();
include '../admin/databaseconnection.php';
if(isset($_GET['id']));
{
        $product_id = $_GET['id'];
        $query = "Select * from Products where id = $product_id";
        $result = mysqli_query($conn,$query);
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        body {
            background: #eef7f1;
            color: #111;
        }

        /* NAVBAR */
        /* ===== NAVBAR ===== */
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
        nav a {
            text-decoration: none;
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

        /* MAIN WRAPPER */
        .product-wrapper {
            max-width: 1200px;
            margin: 80px auto;
            padding: 40px;
            background: white;
            border-radius: 20px;
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            gap: 60px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
        }

        /* IMAGE BOX (FIXED PROBLEM) */
        .product-image {
            width: 100%;
            aspect-ratio: 1/1;
            overflow: hidden;
            border-radius: 20px;
            background: #f1f8f3;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
        }

        .product-image:hover {
            transform: scale(1.02);
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* DETAILS */
        .product-details {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .product-details h1 {
            font-size: 36px;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .price {
            font-size: 28px;
            color: #1f7a3d;
            font-weight: 600;
            margin-bottom: 20px;
        }


        .desc {
            color: #555;
            line-height: 1.8;
            margin-bottom: 25px;
            max-width: 500px;
        }

        /* STOCK */
        .stock {
            font-weight: 600;
            margin-bottom: 25px;
            color: green;
        }

        /* QUANTITY */
        .qty {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 30px;
        }

        .qty button {
            width: 35px;
            height: 35px;
            border: none;
            background: #eee;
            font-size: 18px;
            cursor: pointer;
            border-radius: 8px;
        }

        .qty input {
            width: 60px;
            padding: 8px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        /* BUTTON */
        .buttons {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 14px 32px;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-size: 15px;
            transition: 0.3s;
        }

        .buy {
            background: #1f7a3d;
            color: white;
        }

        .buy:hover {
            background: #14532d;
            transform: translateY(-2px);
            color: white;
        }



        /* RATING */

        .rating {
            color: gold;
            margin-bottom: 15px;
        }


        /* FOOTER */
        footer {
            background: #101010;
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
               .logout_btn{
            background-color: orangered;
            color: white;
            padding: 8px 18px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 20px;
        }

        /* RESPONSIVE */
        @media(max-width:992px) {
            .product-wrapper {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .product-details h1 {
                font-size: 28px;
            }
        }

        @media(max-width:600px) {
            nav {
                flex-direction: column;
                gap: 15px;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
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
        <?php if (isset($_SESSION['name']) && isset($_SESSION['user_email'])) {?>
        <li style="color: white; ">Welcome Back <?php echo htmlspecialchars($_SESSION['name']); ?></li>
        <li><a href="logout.php" style="color: white; background: red; border-radius: 20px; font-size: large; padding: 5px 8px;">Logout</a></li>
        <?php } else { ?>
        <li><a href="register.php">Register</a></li>
        <li><a href="login.php">Login</a></li>
       <?php } ?>
    </ul>
</nav>
<?php $row = mysqli_fetch_assoc($result); ?>
    <!-- PRODUCT DETAIL -->
    <div class="product-wrapper">

        <!-- IMAGE -->
        <div class="product-image">
            <img src="../photos/<?php echo $row['image']; ?>" alt="">
        </div>

        <!-- DETAILS -->
        <div class="product-details">
            <h1><?php echo $row['name']; ?></h1>

            <div class="price">Rs<?php echo $row['price']; ?></div>

            <div class="rating">★★★★★</div>


            <div class="desc">
               <?php echo $row ['description']; ?>
            </div>

            <div class="stock"><?php echo $row['quantity'] ?> items left</div>

            <!-- Quantity -->
            <div class="qty">
                Quantity:
                <input type="number" value="1" min="1">
            </div>

            <!-- Buttons -->
            <div class="buttons">
                <button class="btn buy">Add to Cart</button>
            </div>

        </div>

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
                    <li><a href="index.php">Home</a></li>
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