<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // process the form data and save shipping details to database
    $user_id = $_SESSION['user_id'];
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $user_phone = $_POST['user_phone'];
    $user_address = $_POST['user_address'];
    $total_price = $_POST['total_price'];
    $transaction_uuid = $_POST['transaction_uuid'];

    $_SESSION['name'] = $user_name;
    $_SESSION['email'] = $user_email;
    $_SESSION['phone'] = $user_phone;
    $_SESSION['address'] = $user_address;
    $_SESSION['total_price'] = $total_price;
    $_SESSION['transaction_uuid'] = $transaction_uuid;

    $product_code = "EPAYTEST";
    $message="total_amount=$total_price,transaction_uuid=$transaction_uuid,product_code=$product_code";
    $secret_key ="8gBm/:&EnhH.1/q";
    $hash = hash_hmac('sha256',$message, $secret_key,true);
    $signature = base64_encode($hash);
 

    include '../admin/databaseconnection.php';
    // save shipping details to database
    // $query = "INSERT INTO shipping_details (user_id, name, email, phone, address, total_amt, transaction_uid,payment_method,payment_status) VALUES ('$user_id', '$user_name', '$user_email', '$user_phone', '$user_address', '$total_price', '$transaction_uuid', 'esewa', 'confirmed')";
    // mysqli_query($conn, $query);

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form id="esewa_form" action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST">
        <input type="hidden" id="amount" name="amount" value="<?php echo $total_price - 0; ?>" required>
        <input type="hidden" id="tax_amount" name="tax_amount" value="0" required>
        <input type="hidden" id="total_amount" name="total_amount" value="<?php echo $total_price ?>" required>
        <input type="hidden" id="transaction_uuid" name="transaction_uuid" value="<?php echo $transaction_uuid ?>" required>
        <input type="hidden" id="product_code" name="product_code" value="<?php echo $product_code ?>" required>
        <input type="hidden" id="product_service_charge" name="product_service_charge" value="0" required>
        <input type="hidden" id="product_delivery_charge" name="product_delivery_charge" value="0" required>
        <input type="hidden" id="success_url" name="success_url" value="https://localhost/EcommerceDemo/files/success.php" required>
        <input type="hidden" id="failure_url" name="failure_url" value="https://localhost/EcommerceDemo/files/failure.php" required>
        <input type="hidden" id="signed_field_names" name="signed_field_names"
            value="total_amount,transaction_uuid,product_code" required>
        <input type="hidden" id="signature" name="signature" value="<?php echo $signature; ?>"
            required>
    </form>
</body> 
<script>
  document.getElementById('esewa_form').submit();
</script>
</html>
