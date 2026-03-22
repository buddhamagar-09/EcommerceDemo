<?php
session_start();
include 'databaseconnection.php';
if (!isset($_SESSION['user_email'])) {
    header('location:../files/login.php');
}
else if($_SESSION['user_role'] !== 'admin') {
    header('location:../files/index.php');
}
$sql = "select * from users where userrole = 'user'";
$result = mysqli_query($conn,$sql);
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Products</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Inter',sans-serif;
}

body{
background:#eef7f1;
}

/* Layout */

.dashboard{
display:flex;
}

/* Sidebar */

.sidebar{
width:250px;
height:100vh;
background:#14532d;
color:white;
position:fixed;
padding:30px 20px;
}

.logo{
font-size:20px;
font-weight:600;
margin-bottom:40px;
letter-spacing:1px;
}

.logo a{
color:white;
text-decoration:none;
}

.menu{
list-style:none;
}

.menu li{
margin-bottom:15px;
}

.menu li a{
text-decoration:none;
color:#d1fae5;
display:block;
padding:12px 15px;
border-radius:8px;
transition:0.3s;
}

.menu li a:hover{
background:#1f7a3d;
color:white;
}

/* Main */

.main{
margin-left:250px;
width:100%;
padding:30px;
}

/* Topbar */

.topbar{
background:white;
padding:20px;
border-radius:12px;
display:flex;
justify-content:space-between;
align-items:center;
box-shadow:0 4px 15px rgba(0,0,0,0.05);
}

.logout-btn{
background:#101010;
color:white;
padding:8px 16px;
border-radius:20px;
text-decoration:none;
}

/* Table */

.table-container{
margin-top:30px;
background:white;
padding:25px;
border-radius:12px;
box-shadow:0 5px 20px rgba(0,0,0,0.05);
overflow-x:auto;
}

table{
width:100%;
border-collapse:collapse;
}

thead{
background:#14532d;
color:white;
}

th,td{
padding:14px;
text-align:left;
border-bottom:1px solid #eee;
font-size:14px;
}

tbody tr:hover{
background:#e8f5ec;
}

/* Description column */

.description{
max-width:300px;
/* white-space:nowrap;
overflow:hidden;
text-overflow:ellipsis; */
}
.name{
  max-width:150px;
  
}

/* Image */

img{
width:120px;
height:120px;
border-radius:6px;
object-fit:cover;
}

/* Buttons */

.btn{
padding:6px 12px;
border:none;
border-radius:6px;
cursor:pointer;
font-size:14px;
margin-right:5px;
}

.edit{
background:#1f7a3d;
color:white;
}

.delete{
background:#101010;
color:white;
}

</style>
</head>
<body>
<div class="dashboard">

<!-- Sidebar -->

<div class="sidebar">

<div class="logo"><a href="../index.php">ECOM ADMIN</a></div>

<ul class="menu">
<li><a href="dashboard.php">Dashboard</a></li>
<li><a href="view_users.php">Users</a></li>
<li><a href="addproductform.php">Add Products</a></li>
<li><a href="view_products.php">View Products</a></li>
</ul>

</div>


<!-- Main -->

<div class="main">

<div class="topbar">
<h2>Users</h2>
<a href="../files/logout.php" class="logout-btn">Logout</a>
</div>


<!-- Product Table -->

<div class="table-container">

<table>

<thead>
<tr>
<th>Name</th>
<th>Email</th>
<th>Address</th>
<th>Phone</th>
<th>Role</th>
<th>Signed Date</th>
</tr>
</thead>

<tbody>
<?php while( $row = mysqli_fetch_assoc($result))
{
?>
  <tr>
  <td><?php echo $row['name']; ?></td>
<td><?php echo $row['email']?></td>
<td><?php echo $row['address']?></td>
<td><?php echo $row['phone']?></td>
<td><?php echo $row['userrole']?></td>
<td><?php echo $row['created_at'] ?></td>
</tr>
<?php } ?>

</tbody>
</table>

</div>

</div>

</div>

</body>
</html>