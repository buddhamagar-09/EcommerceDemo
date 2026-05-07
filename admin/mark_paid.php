<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header('location:../files/login.php');
    exit();
} else if ($_SESSION['user_role'] !== 'admin') {
    header('location:../files/index.php');
    exit();
}
if (isset($_GET['id'])) {
    include 'databaseconnection.php';
    $order_id = intval($_GET['id']);

    $mark_query = "update orders set payment_status='paid' where id = '$order_id'";
    $result = mysqli_query($conn, $mark_query);
    if ($result) {
        header("location:view_orders.php");
        exit();
    } else {
        echo "error updating.";
    }
    mysqli_close($conn);
}

?>