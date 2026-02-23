<?php
if(isset($_POST['submit']))
    {
        $name = $_POST['user_name'];
        $email = $_POST['user_email'];
        $password = $_POST['user_password'];
        $address = $_POST['user_address'];
        $phone = $_POST['user_phone'];
        $user_type = "user";

        include "databaseconnection.php";
        $sql = "insert into users (name,email,password,address,phone,userRole) values (?,?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $name, $email, $password, $address, $phone, $user_type);
        $result = $stmt->execute();
        if($result)
            {
                echo "Successfull ";
            }
            else{
                echo "Error coccur".$conn->error;
            }
        $stmt->close();
        $conn->close();
    }
    else{
        echo "all fields are required.";
    }
?>
