<?php

session_start();

require_once "../config/config.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

$sql = "SELECT * FROM users
        WHERE username = ?
        OR email = ?";
    $stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ss",
    $username,
    $username
);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {

    $user = mysqli_fetch_assoc($result);

    if ($user["status"] != "active") {

        $error = "هذا الحساب موقوف.";

    } elseif (password_verify($password, $user["password"])) {

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["full_name"] = $user["full_name"];
        $_SESSION["role"] = $user["role"];

        header("Location: dashboard.php");
        exit();

    } else {

        $error = "كلمة المرور غير صحيحة";

    }

} else {

    $error = "اسم المستخدم أو البريد الإلكتروني غير موجود";

}
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <title>تسجيل الدخول</title>
   <meta name="language" content="Arabic">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #E10000;
            --dark: #3A3A3A;
            --white: #FFFFFF;
        }

        body {
            font-family: 'Cairo', sans-serif;
            min-height: 100vh;
            display: flex;
            background-color: var(--white);
            direction: rtl;
        }

        .login-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            width: 100%;
            min-height: 100vh;
            position: relative;
        }

        .login-container::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: var(--primary);
            z-index: 10;
        }

        .branding-panel {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: var(--white);
            position: relative;
            overflow: hidden;
            padding: 2rem;
        }

        .branding-panel::before {
            display: none;
        }

        .branding-panel::after {
            display: none;
        }

    .logo-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    z-index: 1;
    gap: 20px;
}

.logo-container .logo-icon {
    width: 320px; 
}

.logo-icon img {
    width: 100%;
    height: auto;
    display: block;
}
        .company-name {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--dark);
            letter-spacing: 2px;
            margin-bottom: 0.3rem;
        }

        .company-subtitle {
            font-size: 0.85rem;
            color: var(--dark);
            letter-spacing: 3px;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }

        .company-slogan {
            font-size: 1.2rem;
            color: var(--dark);
            font-weight: 600;
        }

        .form-panel {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            background: var(--white);
            position: relative;
        }

        .form-panel::before {
            display: none;
        }

        .form-wrapper {
            width: 100%;
            max-width: 420px;
            z-index: 1;
        }

        .form-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .form-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .form-header p {
            font-size: 1rem;
            color: #666;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.5rem;
            margin-bottom: 0.6rem;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--dark);
        }

        .label-icon {
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .label-icon.user-icon {
            color: var(--primary);
        }

        .label-icon.lock-icon {
            color: var(--primary);
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper input {
            width: 100%;
            padding: 0.9rem 1.2rem;
            padding-left: 2.8rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-family: 'Cairo', sans-serif;
            font-size: 0.95rem;
            color: var(--dark);
            background: var(--white);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            direction: rtl;
            text-align: right;
        }

        .input-wrapper input::placeholder {
            color: #aaa;
            font-size: 0.9rem;
        }

        .input-wrapper input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(225, 0, 0, 0.1);
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            color: #aaa;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.8rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .remember-me span {
            font-size: 0.85rem;
            color: var(--dark);
            font-weight: 500;
        }

        .forgot-password {
            font-size: 0.85rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #b30000;
            text-decoration: underline;
        }

        .login-btn {
            width: 100%;
            padding: 1rem;
            background: var(--primary);
            color: var(--white);
            border: none;
            border-radius: 10px;
            font-family: 'Cairo', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.7rem;
            transition: background 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 15px rgba(225, 0, 0, 0.3);
        }

        .login-btn:hover {
            background: #c50000;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(225, 0, 0, 0.4);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .login-btn svg {
            width: 22px;
            height: 22px;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            gap: 1rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #ddd;
        }

        .divider span {
            font-size: 0.9rem;
            color: #999;
            font-weight: 500;
        }

        .footer-text {
            text-align: center;
            font-size: 0.8rem;
            color: #888;
            margin-top: 1.5rem;
            padding-top: 1rem;
        }

        .footer-text span {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.3rem;
        }

        @media (max-width: 992px) {
            .login-container {
                grid-template-columns: 1fr;
            }

            .branding-panel {
                display: none;
            }

            .form-panel {
                padding: 2rem 1.5rem;
                min-height: 100vh;
            }
        }

        @media (max-width: 480px) {
            .form-header h1 {
                font-size: 1.6rem;
            }

            .form-wrapper {
                max-width: 100%;
            }

            .login-btn {
                font-size: 1rem;
                padding: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Branding Panel -->
        <div class="branding-panel">
            <div class="logo-container">
                <div class="logo-icon">
                   <img src="../assets/logo.svg" alt="شعارالعقرباوي">
                </div>
                <h2 class="company-name">AL-AQRABAWI</h2>
                <p class="company-subtitle">INTERNATIONAL TRANSPORT & LOGISTICS</p>
                <p class="company-slogan">نصل بك إلى كل مكان</p>
            </div>
        </div>

        <!-- Form Panel -->
        <div class="form-panel">
            <div class="form-wrapper">
                <div class="form-header">
                    <h1>تسجيل الدخول</h1>
                    <p>مرحباً بك في نظام إدارة الفواتير</p>
                </div>

                <?php if(isset($error)): ?>

<div style="
background:#ffe5e5;
color:#b30000;
padding:12px;
margin-bottom:20px;
border-radius:8px;
text-align:center;
font-weight:bold;
">

<?= $error ?>

</div>

<?php endif; ?>

              <form method="POST">
                    <!-- Username -->
                    <div class="form-group">
                        <label for="username">
                            <span class="label-icon user-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </span>
                            اسم المستخدم
                        </label>
                        <div class="input-wrapper">
                            <input type="text" id="username" name="username" placeholder="أدخل اسم المستخدم" required autocomplete="username">
                            <span class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </span>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">
                            <span class="label-icon lock-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" fill="none" stroke="currentColor" stroke-width="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" fill="none" stroke="currentColor" stroke-width="2"/>
                                    <circle cx="12" cy="16" r="1.5" fill="currentColor"/>
                                </svg>
                            </span>
                            كلمة المرور
                        </label>
                        <div class="input-wrapper">
                            <input type="password" id="password" name="password" placeholder="أدخل كلمة المرور" required autocomplete="current-password">
                            <span class="input-icon" id="togglePassword" role="button" aria-label="إظهار كلمة المرور" tabindex="0">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20" id="eyeIcon">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                    <line x1="1" y1="1" x2="23" y2="23" id="eyeSlash"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                    

                    <!-- Options -->
                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox" id="remember" name="remember">
                            <span>تذكرني</span>
                        </label>
                        <a href="#" class="forgot-password">نسيت كلمة المرور؟</a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="login-btn">
                        <span>دخول</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                            <polyline points="10 17 15 12 10 7"/>
                            <line x1="15" y1="12" x2="3" y2="12"/>
                        </svg>
                    </button>
                </form>

              
                <!-- Footer -->
                <div class="footer-text">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 8v4l2 2"/>
                        </svg>
                        جميع الحقوق محفوظة لشركة العقرباوي للنقل والخدمات اللوجستية &copy; 2024
                    </span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeSlash = document.getElementById('eyeSlash');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            eyeSlash.style.display = type === 'password' ? 'block' : 'none';
        });

        togglePassword.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
document.querySelector('.forgot-password').addEventListener('click', function(e) {
    e.preventDefault();
    alert('يرجى التواصل مع مسؤول النظام لإعادة تعيين كلمة المرور.');
});

    </script>
</body>
</html>
