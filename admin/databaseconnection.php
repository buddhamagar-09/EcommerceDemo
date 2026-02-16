<?php 
    $localhost = "localhost";
    $username = "root";
    $pasword = "";
    $database = "EcommerceDemo";


    $conn = new mysqli($localhost, $username, $pasword, $database); 
    if($conn->connect_error){
        die ("Connection Failed: " . $conn->connect_error);
    }
?>