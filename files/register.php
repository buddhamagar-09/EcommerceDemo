<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: Arial, sans-serif;
}

body{
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    background: linear-gradient(135deg, #4facfe, #00f2fe);
    padding:20px;
}

.register-box{
    background:#fff;
    padding:30px 35px;
    width:100%;
    max-width:420px;
    border-radius:10px;
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
}

.register-box h2{
    text-align:center;
    margin-bottom:20px;
    color:#333;
}

.form-group{
    margin-bottom:15px;
}

.form-group label{
    display:block;
    margin-bottom:6px;
    color:#555;
    font-weight:600;
    font-size:14px;
}

.form-group input{
    width:100%;
    padding:11px 12px;
    border:1px solid #ccc;
    border-radius:6px;
    outline:none;
    font-size:14px;
}

.form-group input:focus{
    border-color:#4facfe;
}

.btn{
    width:100%;
    padding:12px;
    border:none;
    border-radius:6px;
    background:#4facfe;
    color:#fff;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
}

.btn:hover{
    background:#2f9cf4;
}

/* 📱 Mobile responsive */
@media (max-width: 480px){
    .register-box{
        padding:20px 20px;
    }

    .register-box h2{
        font-size:20px;
    }

    .form-group label{
        font-size:13px;
    }

    .form-group input{
        font-size:13px;
        padding:10px;
    }

    .btn{
        font-size:15px;
        padding:11px;
    }
}
</style>

</head>
<body>

<div class="register-box">
    <h2>Create Account</h2>
    <form action="" method="post">
        
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="user_name" placeholder="Enter your name" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="user_email" placeholder="Enter your email" required>
        </div>
         <div class="form-group">
            <label>Password</label>
            <input type="password" name="user_password" placeholder="Enter your password" required>
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="user_phone" placeholder="Enter your phone number" required>
        </div>
        <div class="form-group">
            <label>Address</label>
            <input type="text" name="user_address" placeholder="Enter your address" required>
        </div>
        <button type="submit" name="submit" class="btn">Register</button>
    </form>
</div>
</body>
</html>