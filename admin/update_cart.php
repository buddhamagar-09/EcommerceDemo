<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('location:../files/login.php');
}

$user_id = $_SESSION['user_id'];
include '../admin/databaseconnection.php';
$action = isset($_POST['action']) ? $_POST['action'] : '';
$redirect_to = '../files/cart.php';

if ($action === 'update') {
    $cart_id = $_POST['cart_id'];
    $quantity = $_POST['quantity'];
    // check stock avaibality
    // fetch product id from cart using user_id and cart_id
    $product_query = "select c.product_id from cart c where user_id = $user_id and id = $cart_id";
    $product_id_result = mysqli_query($conn, $product_query);
    $row = mysqli_fetch_assoc($product_id_result);
    $product_id = $row['product_id'];

    // checkin stock avaibility
    $stock_query = "select quantity from products where id = $product_id";
    $stock_result = mysqli_query($conn, $stock_query);
    $stock = mysqli_fetch_assoc($stock_result);
    $available_stock = $stock['quantity'];

    if ($quantity > $available_stock) {
        header('Location: ' . $redirect_to);
        exit;
    } else {
        $update_query = "update cart set quantity = $quantity where id = $cart_id and user_id=$user_id";
        $update_result = mysqli_query($conn, $update_query);
        header('Location: ' . $redirect_to);
        exit;
    }
}
if ($action === "remove") {
    $cart_id = $_POST['cart_id'];
    $remove_query = "Delete from cart where id=$cart_id and user_id=$user_id";
    $remove_result = mysqli_query($conn, $remove_query);
    header('Location: ' . $redirect_to);
    exit;
}
$conn->close();

?>