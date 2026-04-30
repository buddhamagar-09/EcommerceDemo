<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>View Orders</title>

	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
			font-family: 'Inter', sans-serif;
		}

		body {
			background: #f0fdfa;
		}

		/* Layout */

		.dashboard {
			display: flex;
		}

		/* Sidebar */

		.sidebar {
			width: 250px;
			height: 100vh;
			background: #115e59;
			color: white;
			position: fixed;
			padding: 30px 20px;
		}

		.logo {
			font-size: 20px;
			font-weight: 600;
			margin-bottom: 40px;
			letter-spacing: 1px;
		}

		.logo a {
			color: white;
			text-decoration: none;
		}

		.menu {
			list-style: none;
		}

		.menu li {
			margin-bottom: 15px;
		}

		.menu li a {
			text-decoration: none;
			color: #99f6e4;
			display: block;
			padding: 12px 15px;
			border-radius: 8px;
			transition: 0.3s;
		}

		.menu li a:hover {
			background: #0f766e;
			color: white;
		}

		/* Main */

		.main {
			margin-left: 250px;
			width: 100%;
			padding: 30px;
		}

		/* Topbar */

		.topbar {
			background: white;
			padding: 20px;
			border-radius: 12px;
			display: flex;
			justify-content: space-between;
			align-items: center;
			box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
		}

		.logout-btn {
			background: #0f172a;
			color: white;
			padding: 8px 16px;
			border-radius: 20px;
			text-decoration: none;
		}

		/* Table */

		.table-container {
			margin-top: 30px;
			background: white;
			padding: 25px;
			border-radius: 12px;
			box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
			overflow-x: auto;
		}

		table {
			width: 100%;
			border-collapse: collapse;
		}

		thead {
			background: #115e59;
			color: white;
		}

		th,
		td {
			padding: 14px;
			text-align: left;
			border-bottom: 1px solid #eee;
			font-size: 14px;
		}

		tbody tr:hover {
			background: #ccfbf1;
		}

		.description {
			max-width: 300px;
		}

		.name {
			max-width: 150px;
		}

		/* Buttons */

		.btn {
			padding: 6px 12px;
			border: none;
			border-radius: 6px;
			cursor: pointer;
			font-size: 14px;
			margin-right: 5px;
			text-decoration: none;
			display: inline-block;
		}

		.view {
			background: #0f766e;
			color: white;
		}

		.delete {
			background: #0f172a;
			color: white;
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
				<li><a href="view_orders.php">View Orders</a></li>
			</ul>

		</div>


		<!-- Main -->

		<div class="main">

			<div class="topbar">
				<h2>View Orders</h2>
				<a href="../files/logout.php" style="color: white; background: #0f172a; border-radius: 20px; font-size: large; padding: 5px 15px;">Logout</a>
			</div>


			<!-- Orders Table -->

			<div class="table-container">

				<table>

					<thead>
						<tr>
							<th>Order ID</th>
							<th>Transaction UID</th>
							<th>Name</th>
							<th>Email</th>
							<th>Phone</th>
							<th class="description">Address</th>
							<th>Total</th>
							<th>Payment Method</th>
							<th>Payment Status</th>
							<th>Action</th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td>101</td>
							<td>txn_abc123</td>
							<td class="name">John Doe</td>
							<td>john@example.com</td>
							<td>+1 555 0100</td>
							<td class="description">123 Main St, Springfield</td>
							<td>49.99</td>
							<td>esewa</td>
							<td>Paid</td>
							<td>
								<a href="#" class="btn view">Details</a>
								<a href="#" class="btn delete">Delete</a>
							</td>
						</tr>
						<tr>
							<td>100</td>
							<td>txn_def456</td>
							<td class="name">Jane Smith</td>
							<td>jane@example.com</td>
							<td>+1 555 0123</td>
							<td class="description">45 Elm St, Metropolis</td>
							<td>129.50</td>
							<td>Cash on Delivery</td>
							<td>Pending</td>
							<td>
								<a href="#" class="btn view">Details</a>
								<a href="#" class="btn delete">Delete</a>
							</td>
						</tr>
						<tr>
							<td>99</td>
							<td>txn_xxx789</td>
							<td class="name">Acme Corp</td>
							<td>sales@acme.com</td>
							<td>+1 555 0199</td>
							<td class="description">789 Industrial Rd, Gotham</td>
							<td>560.00</td>
							<td>esewa</td>
							<td>Paid</td>
							<td>
								<a href="#" class="btn view">Details</a>
								<a href="#" class="btn delete">Delete</a>
							</td>
						</tr>

					</tbody>

				</table>

			</div>

		</div>

	</div>

</body>

</html>
