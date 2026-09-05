<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login | Sexy Wears</title>


<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, Helvetica, sans-serif;
    }

    body {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #F1F5F9;
        padding: 20px;
    }

    .login-box {
        background: #FFFFFF;
        padding: 45px 40px;
        width: 380px;
        border-radius: 12px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 15px 40px rgba(15, 23, 42, 0.10);
        text-align: center;
    }

    .login-box h2 {
        margin-bottom: 8px;
        color: #1E293B;
        font-size: 28px;
    }

    .login-box h2::after {
        content: "";
        display: block;
        width: 45px;
        height: 3px;
        background: #2563EB;
        margin: 12px auto 25px;
        border-radius: 5px;
    }

    .input-box {
        margin-bottom: 20px;
        text-align: left;
    }

    .input-box label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 7px;
    }

    .input-box input {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #CBD5E1;
        border-radius: 8px;
        outline: none;
        font-size: 14px;
        color: #0F172A;
        background: #FFFFFF;
        transition: 0.2s ease;
    }

    .input-box input::placeholder {
        color: #94A3B8;
    }

    .input-box input:focus {
        border-color: #2563EB;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    button {
        width: 100%;
        padding: 12px;
        border: none;
        background: #2563EB;
        color: #FFFFFF;
        font-size: 16px;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.2s ease;
    }

    button:hover {
        background: #1D4ED8;
        transform: translateY(-1px);
    }

    .register-text {
        margin-top: 18px;
        font-size: 14px;
        color: #64748B;
    }

    .register-text a {
        color: #2563EB;
        text-decoration: none;
        font-weight: 600;
    }

    .register-text a:hover {
        color: #1D4ED8;
        text-decoration: underline;
    }

    @media(max-width: 400px) {
        .login-box {
            width: 100%;
            padding: 35px 25px;
        }
    }
</style>


</head>

<body>

<div class="login-box">

    <h2>User Login</h2>

    <form action="../admin/userredirect.php" method="post">

        <div class="input-box">
            <label>Email Address</label>
            <input 
                type="email" 
                name="user_email" 
                placeholder="Enter your email" 
                required
            >
        </div>

        <div class="input-box">
            <label>Password</label>
            <input 
                type="password" 
                name="user_password" 
                placeholder="Enter your password" 
                required
            >
        </div>

        <button type="submit" name="submit">Login</button>

        <p class="register-text">
            Don't have an account?
            <a href="register.php">Register Here</a>
        </p>

    </form>

</div>


</body>

</html>
