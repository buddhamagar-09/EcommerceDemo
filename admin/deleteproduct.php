<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header('location:../files/login.php');
}
else if($_SESSION['user_role'] !== 'admin') {
    header('location:../files/index.php');
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    include 'databaseconnection.php';
    $sql = "delete from products where id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    if ($stmt) {
        header('location:view_products.php');
        exit();
    } else {
        echo "Error deleting product";
    }
    $conn->close();
} else {
    echo "No id provided";
}
