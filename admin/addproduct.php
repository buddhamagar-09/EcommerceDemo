<?php 
session_start();
include 'databaseconnection.php';

$redirect_back = 'addproductform.php';
if (!empty($_SERVER['HTTP_REFERER'])) {
    $referer_path = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);
    if (!empty($referer_path)) {
        $redirect_back = basename($referer_path);
    }
}

if(isset($_POST['addproduct']))
    {
        $name = $_POST['product_name'];
        $description = $_POST['product_description'];
        $price = $_POST['product_price'];
        $quantity = $_POST['product_quantity'];
        $image = $_FILES['product_image']['name'];
        $image_location = $_FILES['product_image']['tmp_name'];
        $upload_location = "../photos/" . $image;
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
                header('location:' . $redirect_back);
                exit();
            }
            else
            {
                header('location:' . $redirect_back);
                exit();
            }
        }
    }
    else
    {
        header('location:' . $redirect_back);
        exit();
    }
?>
