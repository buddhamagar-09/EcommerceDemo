<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .login-box {
            background: white;
            padding: 40px;
            width: 350px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .login-box h2 {
            margin-bottom: 25px;
            color: #333;
        }

        .input-box {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-box label {
            font-size: 14px;
            color: #555;
        }

        .input-box input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
            transition: 0.3s;
        }

        .input-box input:focus {
            border-color: #764ba2;
        }

        button {
            width: 100%;
            padding: 10px;
            border: none;
            background: #667eea;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #5a67d8;
        }

        @media(max-width:400px) {
            .login-box {
                width: 90%;
                padding: 30px;
            }
        }
    </style>
</head>

<body>

    <div class="login-box">
        <h2>User Login</h2>

        <form action="#" method="post">

            <div class="input-box">
                <label>Email</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="input-box">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" name="submit">Login</button>
            <p style="margin-top: 10px;">
                Don't have an account?
                <a href="register.php">Register Here!!</a>
            </p>


        </form>
    </div>

</body>

</html>