<?php
session_start();
include '../admin/databaseconnection.php';
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
$sql = "Select * from products";
$result = mysqli_query($conn, $sql);
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
   <style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:"Segoe UI",sans-serif;
}

body{
    background:#f0fdfa;
}

/* ===== NAVBAR ===== */
nav{
    background:linear-gradient(to right,#115e59,#0f766e);
    padding:28px 70px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
}
nav h2{
    color:#fff;
    font-size:28px;
}
nav ul{
    list-style:none;
    display:flex;
    gap:32px;
}
nav ul li a{
    color:#fff;
    text-decoration:none;
    font-size:16px;
    font-weight:500;
}
nav ul li a:hover{
    opacity:0.8;
}

/* ===== HERO (PROFESSIONAL) ===== */
.hero{
    background:linear-gradient(135deg,#14b8a6,#0f766e);

    padding:130px 20px;
    text-align:center;
    color:white;
}
.hero h1{
    font-size:62px;
    letter-spacing:1px;
}
.hero p{
    margin:18px auto;
    font-size:20px;
    max-width:650px;
    opacity:0.95;
}
.hero-buttons{
    margin-top:35px;
}
.hero-buttons button{
    padding:15px 38px;
    font-size:16px;
    border-radius:30px;
    border:none;
    cursor:pointer;
    margin:0 10px;
}
.hero-buttons .primary{
    background:#0f172a;
    color:#fff;
    font-weight:600;
    border:2px solid #fff;
}
.hero-buttons .secondary{
    background:transparent;
    color:white;
    border:2px solid white;
}
.hero-buttons button:hover{
    transform:translateY(-2px);
}

/* ===== PRODUCTS ===== */
.products{
    padding:80px 70px;
}
.products h2{
    text-align:center;
    margin-bottom:55px;
    font-size:34px;
}
.product-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:35px;
}
.card{
    background:#fff;
    border-radius:16px;
    padding:22px;
    text-align:center;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
    transition:0.3s;
}
.card:hover{
    transform:translateY(-10px);
}
.card img{
    width:100%;
    height:220px;
    object-fit:contain;
    background:#ecfeff;
    border-radius:12px;
}
.card h3{
    margin:18px 0 10px;
    font-size:20px;
}
.card p{
    font-size:14px;
    color:#666;
}
.price{
    margin:15px 0;
    font-size:18px;
    font-weight:bold;
    color:#0f766e;
}
.card-actions{
    display:grid;
    grid-template-columns:1fr;
    gap:10px;
    margin-top:12px;
}
.card-btn{
    width:100%;
    padding:12px 18px;
    border:none;
    border-radius:999px;
    cursor:pointer;
    font-weight:600;
    font-size:14px;
    transition:transform .2s ease,box-shadow .2s ease,background .2s ease;
}
.card-btn:hover{
    transform:translateY(-2px);
}
.add-cart-btn{
    background:#0f766e;
    color:white;
    box-shadow:0 8px 20px rgba(15,118,110,.22);
}
.add-cart-btn:hover{
    background:#115e59;
}
.details-btn{
    background:#ecfeff;
    color:#0f766e;
    border:1px solid #99f6e4;
}
.details-btn:hover{
    background:#cffafe;
    box-shadow:0 8px 20px rgba(34,197,94,.12);
}

/* ===== FOOTER ===== */
footer{
    background:#0f172a;
    color:#ccc;
    padding:80px 70px;
}
.footer-grid{
    max-width:1200px;
    margin:auto;
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:45px;
}
footer h4{
    color:white;
    margin-bottom:20px;
}
footer ul{
    list-style:none;
}
footer ul li{
    margin-bottom:12px;
    font-size:14px;
}
.copy{
    text-align:center;
    margin-top:55px;
    font-size:14px;
    color:#aaa;
}

/* ===== RESPONSIVE ===== */
@media(max-width:992px){
    .product-grid{
        grid-template-columns:repeat(2,1fr);
    }
}
@media(max-width:600px){
    nav{
        justify-content:center;
        gap:20px;
    }
    nav ul{
        flex-wrap:wrap;
        justify-content:center;
    }
    .product-grid{
        grid-template-columns:1fr;
    }
    .hero h1{
        font-size:42px;
    }
}
</style>
</head>
<body>

<!-- NAVBAR -->
  <nav>
        <h2>Sexy Wears</h2>
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
  <section class="products">
        <h2>Our Products</h2>
        <div class="product-grid">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="card">
                    <img src="../photos/<?php echo $row['image']; ?>">
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
                        <a href="product_details.php?id=<?php echo $row['id']; ?>"><button type="button" class="card-btn details-btn">View Details</button></a>
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