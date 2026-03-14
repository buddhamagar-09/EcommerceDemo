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
    if (move_uploaded_file($image_location,$upload_location))
        {
            $query = $conn ->prepare("Insert into products(name,description,price,quantity,image) values(?,?,?,?,?)");
            $query ->bind_param("ssiis",$name,$description,$price,$quantity,$image);
            $query ->execute();
            if($query)
            {
                echo "<script>alert('Product added successfully');</script>";
                echo "<script>window.location.href='productlist.php';</script>";
            }
            else
            {
                echo "<script>alert('Failed to add product');</script>";
                echo "<script>window.location.href='addproduct.php';</script>";
            }
        }
    }
    else
    {
        echo "<script>alert('Please fill all fields');</script>";
        echo "<script>window.location.href='addproduct.php';</script>";
    }
?>