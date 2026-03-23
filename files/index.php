<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Ecom</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:"Segoe UI",sans-serif;
}

body{
    background:#eef7f1;
}

/* ===== NAVBAR ===== */
nav{
    background:linear-gradient(to right,#14532d,#1f7a3d);
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
    background:linear-gradient(135deg,#2f8a4f,#1f7a3d);

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
    background:#101010;
    color:#fff;
    font-weight:600;
    padding:15px 38px;
    font-size:16px;
    border-radius:30px;
    border:2px solid #fff;
    text-decoration: none;
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
    background:#f1f8f3;
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
    color:#1f7a3d;
}
.card button{
    padding:12px 28px;
    background:#1f7a3d;
    color:white;
    border:none;
    border-radius:25px;
    cursor:pointer;
}
.card button:hover{
    background:#14532d;
}

/* ===== FOOTER ===== */
footer{
    background:#101010;
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

<!-- HERO -->
<section class="hero">
    <h1>Online Shopping Made Easy</h1>
    <p>Discover premium products, trusted quality and unbeatable prices — all in one place.</p>
    <div class="hero-buttons">
        <a href="products.php" class="primary">Shop Now</a>
        <button class="secondary">Explore</button>
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

    <div class="product-grid">
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
            <div class="card">
                <img src="../photos/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                <h3><?php echo $row['name']; ?></h3>
                <p><?php echo $row['description']; ?></p>
                <div class="price">$<?php echo $row['price']; ?></div>
               
               <a href="product_details.php?id=<?php echo $row['id']; ?>"><button>Buy Now</button> </a> 
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