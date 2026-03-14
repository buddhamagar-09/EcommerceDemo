<?php
session_start();

include "databaseconnection.php";

if (isset($_POST['submit'])) {
    $email = $_POST['user_email'];
    $password = $_POST['user_password'];

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if ($row['userrole'] == 'admin') {
            header("Location: ../admin/dashboard.php");
            $_SESSION['name'] = $row['name'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_role'] = $row['userrole'];
        } elseif ($row['userrole'] == 'user') {
            $_SESSION['name'] = $row['name'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_role'] = $row['userrole'];
            header("Location: ../files/index.php");
        }
    } else {
        echo "<script>alert('Invalid email or password. Please try again.');</script>";
    }
}
?>