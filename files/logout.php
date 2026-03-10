<?php
session_start();
if (isset($_SESSION['user_email']) && isset($_SESSION['user_role']) && isset($_SESSION['user_name'])) {
    session_destroy();
    header("Location: login.php");
} else {
    header("Location: index.php");
}
?>