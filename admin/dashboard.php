<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header('location:../files/login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Inter',sans-serif;
}

body{
    background:#f4f6f9;
}

/* Layout */
.dashboard{
    display:flex;
    min-height:100vh;
}

/* Sidebar */
.sidebar{
    width:260px;
    background:#111827;
    color:white;
    padding:30px 20px;
    position:fixed;
    height:100%;
}

.logo{
    font-size:20px;
    font-weight:600;
    margin-bottom:40px;
    letter-spacing:1px;
}
.logo a{
    color:white;
    text-decoration: none;
}

.menu{
    list-style:none;
}

.menu li{
    margin-bottom:15px;
}

.menu li a{
    text-decoration:none;
    color:#9ca3af;
    display:block;
    padding:12px 15px;
    border-radius:8px;
    transition:0.3s;
}

.menu li a:hover,
.menu li.active a{
    background:#1f2937;
    color:white;
}

/* Main */
.main{
    margin-left:260px;
    flex:1;
    padding:30px;
}

/* Topbar */
.topbar{
    background:white;
    padding:20px 30px;
    border-radius:12px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 4px 20px rgba(0,0,0,0.05);
}

.topbar h2{
    font-weight:600;
    color:#111827;
}

.profile{
    display:flex;
    align-items:center;
    gap:15px;
}

.logout-btn{
    background:#ef4444;
    color:white;
    padding:8px 18px;
    border-radius:20px;
    text-decoration:none;
    font-size:14px;
}

/* Stats Cards */
.stats{
    margin-top:30px;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
}

.card{
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 5px 25px rgba(0,0,0,0.05);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-5px);
}

.card h3{
    font-size:28px;
    margin-bottom:8px;
    color:#111827;
}

.card p{
    color:#6b7280;
    font-size:14px;
}

/* Content Section */
.content{
    margin-top:30px;
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 5px 25px rgba(0,0,0,0.05);
    line-height:1.8;
    color:#374151;
}

/* Responsive */
@media(max-width:768px){

    .sidebar{
        position:relative;
        width:100%;
        height:auto;
    }

    .main{
        margin-left:0;
        padding:20px;
    }

}

</style>
</head>
<body>

<div class="dashboard">

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo"><a href="../index.php">ECOM ADMIN</a></div>

        <ul class="menu">
            <li class="active"><a href="#">Dashboard</a></li>
            <li><a href="#">Users</a></li>
            <li><a href="addproduct.php">Add Products</a></li>
            <li><a href="#">View Products</a></li>
        </ul>
    </aside>

    <!-- Main -->
    <div class="main">

        <div class="topbar">
            <h2>Dashboard</h2>
            <div class="profile">
                <span>Admin</span>
                <a href="../files/logout.php" class="logout-btn">Logout</a>
            </div>
        </div>

        <!-- Stats -->
        <div class="stats">
            <div class="card">
                <h3>1,245</h3>
                <p>Total Users</p>
            </div>
            <div class="card">
                <h3>320</h3>
                <p>Total Products</p>
            </div>
            <div class="card">
                <h3>$8,540</h3>
                <p>Total Revenue</p>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Quod quos consequuntur aut optio adipisci ad quasi quis earum recusandae voluptatibus ducimus mollitia et ipsa commodi enim ex hic tenetur iusto odio rerum veniam perspiciatis, sed sequi. Delectus consectetur dicta molestias totam ex optio! Eius quidem iste perferendis, laudantium laboriosam, exercitationem sunt dolores explicabo expedita nemo, reprehenderit eligendi molestiae? Molestias vero id eos quia alias inventore quasi in adipisci sint ex sunt aspernatur quisquam nulla nobis labore tempora numquam nostrum optio voluptatum laboriosam, neque modi.
            </p>
        </div>

    </div>

</div>

</body>
</html>