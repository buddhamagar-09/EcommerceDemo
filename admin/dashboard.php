<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header('location:../files/login.php');
} elseif ($_SESSION['user_role'] == 'admin') {
    header('location:dashboard.php');
}
?>
admin Page 
<a href="../files/logout.php">Logout</a>