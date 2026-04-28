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
    font-family:"Segoe UI", Arial, sans-serif;
}

body{
    min-height:100vh;
    display:grid;
    place-items:center;
    padding:24px;
    background:
        radial-gradient(circle at top left, rgba(45, 212, 191, 0.22), transparent 28%),
        radial-gradient(circle at bottom right, rgba(59, 130, 246, 0.18), transparent 30%),
        linear-gradient(135deg, #042f2e 0%, #0f172a 52%, #111827 100%);
    color:#e5e7eb;
}

.page-shell{
    width:100%;
    max-width:980px;
    display:grid;
    grid-template-columns:1.05fr 0.95fr;
    gap:22px;
    align-items:stretch;
}

.brand-panel,
.register-box{
    border:1px solid rgba(255,255,255,0.12);
    border-radius:24px;
    box-shadow:0 28px 60px rgba(0,0,0,0.28);
    backdrop-filter:blur(14px);
    -webkit-backdrop-filter:blur(14px);
}

.brand-panel{
    padding:40px;
    background:linear-gradient(160deg, rgba(15, 23, 42, 0.78), rgba(4, 120, 87, 0.38));
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    position:relative;
    overflow:hidden;
}

.brand-panel::before,
.brand-panel::after{
    content:"";
    position:absolute;
    border-radius:50%;
    background:rgba(255,255,255,0.08);
    pointer-events:none;
}

.brand-panel::before{
    width:180px;
    height:180px;
    top:-60px;
    right:-50px;
}

.brand-panel::after{
    width:120px;
    height:120px;
    bottom:-40px;
    left:-30px;
}

.brand-badge{
    width:56px;
    height:56px;
    border-radius:18px;
    display:grid;
    place-items:center;
    background:linear-gradient(135deg, #5eead4, #22c55e);
    color:#052e2b;
    font-size:24px;
    font-weight:800;
    margin-bottom:28px;
    box-shadow:0 18px 40px rgba(45, 212, 191, 0.28);
    position:relative;
    z-index:1;
}

.brand-panel h1{
    font-size:clamp(30px, 4vw, 46px);
    line-height:1.05;
    color:#f8fafc;
    margin-bottom:14px;
    position:relative;
    z-index:1;
}

.brand-panel p{
    max-width:420px;
    color:#cbd5e1;
    line-height:1.7;
    font-size:15px;
    position:relative;
    z-index:1;
}

.feature-list{
    list-style:none;
    display:grid;
    gap:14px;
    margin-top:32px;
    position:relative;
    z-index:1;
}

.feature-list li{
    display:flex;
    align-items:flex-start;
    gap:12px;
    color:#e2e8f0;
    font-size:14px;
    line-height:1.55;
}

.feature-list li span{
    width:26px;
    height:26px;
    border-radius:50%;
    display:grid;
    place-items:center;
    background:rgba(255,255,255,0.14);
    color:#d1fae5;
    flex:0 0 26px;
}

.brand-footer{
    margin-top:36px;
    padding-top:18px;
    border-top:1px solid rgba(255,255,255,0.12);
    color:#cbd5e1;
    font-size:13px;
    position:relative;
    z-index:1;
}

.register-box{
    background:rgba(255,255,255,0.95);
    padding:34px;
    width:100%;
    color:#0f172a;
}

.register-box h2{
    text-align:center;
    margin-bottom:10px;
    color:#0f172a;
    font-size:28px;
}

.register-subtitle{
    text-align:center;
    color:#64748b;
    font-size:14px;
    line-height:1.6;
    margin-bottom:26px;
}

.helper-pill{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:10px 14px;
    border-radius:999px;
    background:rgba(15,118,110,0.08);
    color:#0f766e;
    font-size:13px;
    font-weight:700;
    margin-bottom:18px;
}

.divider{
    display:flex;
    align-items:center;
    gap:12px;
    margin:18px 0 20px;
    color:#94a3b8;
    font-size:12px;
    text-transform:uppercase;
    letter-spacing:0.14em;
}

.divider::before,
.divider::after{
    content:"";
    height:1px;
    background:#e2e8f0;
    flex:1;
}

.form-row{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:14px;
}

.form-group{
    margin-bottom:16px;
}

.form-group label{
    display:block;
    margin-bottom:6px;
    color:#334155;
    font-weight:700;
    font-size:13px;
}

.form-group input{
    width:100%;
    padding:13px 14px;
    border:1px solid #cbd5e1;
    border-radius:14px;
    outline:none;
    font-size:15px;
    background:#f8fafc;
    color:#0f172a;
    transition:border-color 0.25s ease, box-shadow 0.25s ease, transform 0.25s ease;
}

.form-group input:focus{
    border-color:#0f766e;
    box-shadow:0 0 0 4px rgba(15,118,110,0.14);
    background:#fff;
    transform:translateY(-1px);
}

.btn{
    width:100%;
    padding:14px;
    border:none;
    border-radius:14px;
    background:linear-gradient(135deg, #0f766e, #115e59);
    color:#fff;
    font-size:16px;
    font-weight:800;
    cursor:pointer;
    transition:transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
    box-shadow:0 16px 30px rgba(15,118,110,0.24);
    margin-top:8px;
}

.btn:hover{
    transform:translateY(-1px);
    filter:brightness(1.03);
    box-shadow:0 18px 34px rgba(15,118,110,0.3);
}

.form-note{
    margin-top:16px;
    color:#64748b;
    font-size:13px;
    line-height:1.6;
    text-align:center;
}

.form-note a{
    color:#0f766e;
    font-weight:700;
    text-decoration:none;
}

.form-note a:hover{
    text-decoration:underline;
}

.form-actions{
    margin-top:6px;
}

.register-box form{
    margin-top:4px;
}

@media (max-width: 900px){
    .page-shell{
        grid-template-columns:1fr;
    }

    .brand-panel{
        min-height:unset;
    }
}

@media (max-width: 640px){
    body{
        padding:16px;
    }

    .register-box,
    .brand-panel{
        border-radius:20px;
    }

    .register-box{
        padding:26px 20px;
    }

    .brand-panel{
        padding:26px 20px;
    }

    .brand-panel h1{
        font-size:28px;
    }

    .form-row{
        grid-template-columns:1fr;
    }

    .register-box h2{
        font-size:24px;
    }

    .register-subtitle,
    .form-note,
    .brand-panel p{
        font-size:13px;
    }

    .form-group label{
        font-size:12px;
    }

    .form-group input,
    .btn{
        font-size:14px;
    }
}

@media (max-width: 420px){
    .helper-pill{
        width:100%;
        justify-content:center;
        text-align:center;
    }

    .register-box{
        padding:22px 16px;
    }

    .brand-panel{
        padding:22px 16px;
    }

    .btn{
        padding:13px;
    }
}
</style>

</head>
<body>

<main class="page-shell">
    <section class="brand-panel">
        <div>
            <div class="brand-badge">E</div>
            <h1>Create your account in a cleaner, faster flow.</h1>
            <p>Join the store to save your details, track orders, and get a smoother checkout experience every time you shop.</p>

            <ul class="feature-list">
                <li><span>✓</span> Quick sign-up with a polished, mobile-friendly layout.</li>
                <li><span>✓</span> Secure details capture for orders and account access.</li>
                <li><span>✓</span> Built to match the existing teal and navy brand style.</li>
            </ul>
        </div>

        <div class="brand-footer">
            Already have an account? Use the login page to continue shopping.
        </div>
    </section>

    <section class="register-box">
        <div class="helper-pill">New customer registration</div>
        <h2>Create Account</h2>
        <p class="register-subtitle">Fill in your details below to set up your account and start shopping.</p>

        <div class="divider">Account details</div>

        <form action="../admin/insert.php" method="post">
            <div class="form-row">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="user_name" placeholder="Enter your name" required>
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="user_phone" placeholder="Enter your phone number" required>
                </div>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="user_email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="user_password" placeholder="Create a strong password" required>
            </div>

            <div class="form-group">
                <label>Address</label>
                <input type="text" name="user_address" placeholder="Enter your address" required>
            </div>

            <div class="form-actions">
                <button type="submit" name="submit" class="btn">Register</button>
            </div>

            <p class="form-note">
                By registering, you can manage your orders more easily.
                <a href="login.php">Already have an account? Login here.</a>
            </p>
        </form>
    </section>
</main>

</body>
</html>