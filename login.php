<?php
session_start();
require_once 'db.php';

// Redirect if user is already logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    if (($_SESSION['role'] ?? '') === 'admin') {
        header('Location: admin.php');
    } else {
        header('Location: index.php');
    }
    exit;
}

$error_message = '';
$success_message = '';
$email = '';

if (isset($_GET['registered']) && $_GET['registered'] === 'admin') {
    $success_message = 'Administrator account registered successfully. Please log in.';
}

// Handle login submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error_message = 'Please enter both your email address and password.';
    } else {
        try {
            // Authenticate credentials against database with distinct named parameters
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email OR username = :username LIMIT 1");
            $stmt->execute([
                'email'    => $email,
                'username' => $email
            ]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['logged_in']      = true;
                $_SESSION['user_id']        = $user['id'];
                $_SESSION['user_email']     = $user['email'];
                $_SESSION['user_name']      = ($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '');
                $_SESSION['username']       = $user['username'];
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['role']           = $user['role'];

                // Check administrator role and redirect to admin.php
                if ($user['role'] === 'admin') {
                    header('Location: admin.php');
                } else {
                    header('Location: index.php');
                }
                exit;
            } else {
                $error_message = 'Invalid credentials. Please try again.';
            }
        } catch (PDOException $e) {
            $error_message = 'Database error: ' . $e->getMessage();
        }
    }
}

$nav_links = [
    ['label' => 'Home',      'href' => 'index.php'],
    ['label' => 'Shop',      'href' => 'shop.php'],
    ['label' => 'News',      'href' => 'news.php'],
    ['label' => 'Training',  'href' => 'training.php'],
    ['label' => 'Lifestyle', 'href' => 'lifestyle.php'],
    ['label' => 'About',     'href' => 'about.php'],
];

$footer_columns = [
    'Policy'            => ['Shipping & Returns', 'Store Policy', 'Payment Methods', 'Cookies Policy', 'Terms on Use'],
    'Our Store'         => ['Salngan, Mayabon Street', 'Zamboanguita, Negros Oriental', 'Tel: 09876543210', 'Email: thetension100@gmail.com'],
    'Customer Service'  => ['Tel: 09876543210', 'Email: thetension100@gmail.com'],
];

$social_links = ['facebook', 'instagram', 'tiktok', 'pinterest', 'youtube', 'twitter'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TENSION — Login</title>
    <meta name="description" content="Log in to your TENSION account.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=EB+Garamond:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="style.css">
    <style>
        .login-hero {
            min-height: calc(100vh - 120px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            background-image: linear-gradient(rgba(15, 15, 15, 0.85), rgba(15, 15, 15, 0.85)), url('images/background-2.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background: rgba(22, 22, 22, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-header h1 {
            font-family: 'Anton', sans-serif;
            font-size: 2.5rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 8px;
            color: #ffffff;
        }

        .login-header p {
            color: #a0a0a0;
            font-size: 0.95rem;
        }

        .error-alert {
            background-color: rgba(220, 53, 69, 0.15);
            border: 1px solid rgba(220, 53, 69, 0.4);
            color: #ff6b6b;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 0.9rem;
            margin-bottom: 24px;
        }

        .success-alert {
            background-color: rgba(139, 197, 63, 0.15);
            border: 1px solid rgba(139, 197, 63, 0.4);
            color: #8BC53F;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 0.9rem;
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            color: #cccccc;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            background: #111111;
            border: 1px solid #333333;
            border-radius: 8px;
            color: #ffffff;
            font-size: 1rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: #8BC53F;
            box-shadow: 0 0 0 3px rgba(139, 197, 63, 0.2);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            font-size: 0.88rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #a0a0a0;
            cursor: pointer;
        }

        .remember-me input[type="checkbox"] {
            accent-color: #8BC53F;
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .forgot-password {
            color: #8BC53F;
            text-decoration: none;
            transition: color 0.2s;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            font-size: 1rem;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            border: none;
            border-radius: 8px;
            background: #8BC53F;
            color: #000000;
            transition: background 0.2s ease, transform 0.1s ease;
        }

        .btn-submit:hover {
            background: #9be043;
        }

        .login-footer-text {
            text-align: center;
            margin-top: 28px;
            font-size: 0.9rem;
            color: #888888;
        }

        .login-footer-text a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 600;
        }

        .login-footer-text a:hover {
            color: #8BC53F;
        }
    </style>
</head>
<body>

    <header class="site-header">
        <a href="index.php" class="logo">
            <span class="logo-word"><span class="accent">T</span>ension</span>
        </a>

        <nav class="main-nav" aria-label="Main navigation">
            <?php foreach ($nav_links as $link): ?>
                <a href="<?= htmlspecialchars($link['href']) ?>" class="nav-link">
                    <?= htmlspecialchars(strtoupper($link['label'])) ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="header-actions">
            <a href="login.php" class="header-icon-link active" aria-label="Login" title="Login">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M4 20c1.5-4 5-6 8-6s6.5 2 8 6"/>
                </svg>
            </a>
        </div>
    </header>

    <main class="login-hero">
        <div class="login-card">
            <div class="login-header">
                <h1>Welcome <span class="accent">Back</span></h1>
                <p>Log in to access your account</p>
            </div>

            <?php if (!empty($success_message)): ?>
                <div class="success-alert" role="alert">
                    <?= htmlspecialchars($success_message) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <div class="error-alert" role="alert">
                    <?= htmlspecialchars($error_message) ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="post" novalidate>
                <div class="form-group">
                    <label for="email">Email or Username</label>
                    <input type="text" id="email" name="email" class="form-control" placeholder="you@example.com" value="<?= htmlspecialchars($email) ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" value="1">
                        <span>Remember me</span>
                    </label>
                    <a href="#" class="forgot-password">Forgot password?</a>
                </div>

                <button type="submit" class="btn-submit">Sign In</button>
            </form>

            <p class="login-footer-text">
                Don't have an account? <a href="register.php">Create One</a>
            </p>
        </div>
    </main>

    <footer class="site-footer">
        <div class="footer-bottom">
            <span>&copy; <?= date('Y') ?> — TENSION Energy Drink Company LLC. All Rights Reserved.</span>
        </div>
    </footer>

</body>
</html>