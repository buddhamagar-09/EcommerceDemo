<?php 
if(isset($_POST['editproduct']))
    {
        $product_id = (int) $_POST['product_id'];
        $name = $_POST['product_name'] ?? '';
        $description = $_POST['product_description'] ?? '';
        $price = $_POST['product_price'] ?? '';
        $quantity = $_POST['product_quantity'] ?? '';

        if(isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK && $_FILES['product_image']['name'] !== '') {
            $image = $_FILES['product_image']['name'];
            $image_tmp = $_FILES['product_image']['tmp_name'];
            $upload_location = '../photos/' . $image;
            move_uploaded_file($image_tmp, $upload_location);
        } else {
            $image = $_POST['current_image'];
        }
        include 'databaseconnection.php';
        $update_sql = "UPDATE products SET name=?, description=?, price=?, quantity=?, image=? WHERE id=?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssdisi", $name, $description, $price, $quantity, $image, $product_id);
        $result = $stmt->execute();
        if($result)
            {
                echo "<script>alert('Product updated successfully!'); window.location.href='view_products.php';</script>";
            }
        else{
                echo "<script>alert('Error: Product could not be updated. Please try again.'); history.back();</script>";
        }
        $conn->close();
    }
    else{
        echo "<script>alert('All fields are required.'); history.back();</script>";
    }
?>