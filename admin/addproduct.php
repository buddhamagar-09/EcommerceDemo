<?php 
session_start();
include 'databaseconnection.php';

if(isset($_POST['addproduct']))
    {
        $name = $_POST['product_name'];
        $description = $_POST['product_description'];
        $price = $_POST['product_price'];
        $quantity = $_POST['product_quantity'];
        $image = $_FILES['product_image']['name'];
        $image_location = $_FILES['product_image']['temp_name'];
        $upload_location = "../image/";
        // $_FILES[' product_image'] = {
        // 'name = 'nag.jpg'
        //     'type' = 'image.jpg/png';
        //     'temp_name' = '/temp/1234.temp';temporary location
        //     in server 
        //     'error' = 0
        //     'size' = 2300000
        // }
    }
?>