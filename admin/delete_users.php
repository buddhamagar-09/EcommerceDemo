<?php
session_start();
include 'databaseconnection.php';
if (!isset($_SESSION['user_email'])) {
    header('location:../files/login.php');
} else if ($_SESSION['user_role'] !== 'admin') {
    header('location:../files/index.php');
}
if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    $remove_query = "Update users set status='inactive' where id = '$user_id'";
    $result = mysqli_query($conn, $remove_query);
    if ($result) {
        header('location:view_users.php');
        exit();
    } else {
        echo "error removing users !!";
        exit();
    }
}
mysqli_close($conn);

?>