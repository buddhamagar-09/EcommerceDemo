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
background:#f4f6f9;
}

/* Layout */

.dashboard{
display:flex;
}

/* Sidebar */

.sidebar{
width:250px;
height:100vh;
background:#111827;
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
color:#9ca3af;
display:block;
padding:12px 15px;
border-radius:8px;
transition:0.3s;
}

.menu li a:hover{
background:#1f2937;
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
background:#ef4444;
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
background:#111827;
color:white;
}

th,td{
padding:14px;
text-align:left;
border-bottom:1px solid #eee;
font-size:14px;
}

tbody tr:hover{
background:#f9fafb;
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
background:#3b82f6;
color:white;
}

.delete{
background:#ef4444;
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
<li><a href="#">Users</a></li>
<li><a href="addproduct.php">Add Products</a></li>
<li><a href="viewproduct.php">View Products</a></li>
</ul>

</div>


<!-- Main -->

<div class="main">

<div class="topbar">
<h2>View Products</h2>
<a href="../files/logout.php" class="logout-btn">Logout</a>
</div>


<!-- Product Table -->

<div class="table-container">

<table>

<thead>
<tr>
<th>Image</th>
<th>Name</th>
<th>Description</th>
<th>Price</th>
<th>Quantity</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<tr>
<td><img src="https://via.placeholder.com/60"></td>
<td>Perfume</td>
<td class="description">Elevate your everyday style with this elegant Gucci GG Supreme Small Shoulder Bag. Crafted from signature GG monogram canvas in beige and ebony tones, this timeless piece features rich brown leather trim and a polished gold-tone double GG logo at the front. The slim shoulder strap ensures comfortable carrying, while the zip-top closure keeps your essentials secure. Perfect for casual outings or evening wear, this compact yet stylish bag adds a luxurious touch to any outfit.</td>
<td>2500</td>
<td>10</td>
<td>
<button class="btn edit">Edit</button>
<button class="btn delete">Delete</button>
</td>
</tr>

</tbody>

</table>

</div>

</div>

</div>

</body>
</html>