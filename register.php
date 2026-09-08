<?php
session_start();
require_once 'db.php';

// Redirect if user is already logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$error_message = '';
$first_name = '';
$last_name  = '';
$username   = '';
$email      = '';
$phone      = '';
$address    = '';

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name       = trim($_POST['first_name'] ?? '');
    $last_name        = trim($_POST['last_name'] ?? '');
    $username         = trim($_POST['username'] ?? '');
    $email            = trim($_POST['email'] ?? '');
    $phone            = trim($_POST['phone'] ?? '');
    $address          = trim($_POST['address'] ?? '');
    $password         = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $terms            = isset($_POST['terms']);

    // Field validations
    if (empty($first_name) || empty($last_name) || empty($username) || empty($email) || empty($phone) || empty($address) || empty($password) || empty($confirm_password)) {
        $error_message = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Please enter a valid email address.';
    } elseif ($password !== $confirm_password) {
        $error_message = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error_message = 'Password must be at least 6 characters long.';
    } elseif (!$terms) {
        $error_message = 'You must agree to the Terms on Use and Policy.';
    } else {
        try {
            // Check if username or email already exists in the database
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email OR username = :username LIMIT 1");
            $stmt->execute([
                'email'    => $email,
                'username' => $username
            ]);

            if ($stmt->fetch()) {
                $error_message = 'Username or Email address is already registered.';
            } else {
                // Securely hash the user password
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                // Insert new user record into database
                $insert_stmt = $pdo->prepare("
                    INSERT INTO users (first_name, last_name, username, email, phone, address, password)
                    VALUES (:first_name, :last_name, :username, :email, :phone, :address, :password)
                ");

                $success = $insert_stmt->execute([
                    'first_name' => $first_name,
                    'last_name'  => $last_name,
                    'username'   => $username,
                    'email'      => $email,
                    'phone'      => $phone,
                    'address'    => $address,
                    'password'   => $hashed_password,
                ]);

                if ($success) {
                    // Set session variables and log user in automatically
                    $_SESSION['logged_in']  = true;
                    $_SESSION['user_id']    = $pdo->lastInsertId();
                    $_SESSION['user_email'] = $email;
                    $_SESSION['user_name']  = $first_name . ' ' . $last_name;
                    $_SESSION['username']   = $username;

                    header('Location: index.php');
                    exit;
                } else {
                    $error_message = 'Registration failed. Please try again.';
                }
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
    <title>TENSION — Create Account</title>
    <meta name="description" content="Create a TENSION account to unlock exclusive gear and offers.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=EB+Garamond:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="style.css">
    <style>
        .register-hero {
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

        .register-card {
            width: 100%;
            max-width: 580px;
            background: rgba(22, 22, 22, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
        }

        .register-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .register-header h1 {
            font-family: 'Anton', sans-serif;
            font-size: 2.5rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 8px;
            color: #ffffff;
        }

        .register-header p {
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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        @media (max-width: 576px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
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
            align-items: center;
            margin-bottom: 24px;
            font-size: 0.88rem;
        }

        .terms-check {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            color: #a0a0a0;
            cursor: pointer;
            line-height: 1.4;
        }

        .terms-check input[type="checkbox"] {
            accent-color: #8BC53F;
            width: 16px;
            height: 16px;
            margin-top: 2px;
            cursor: pointer;
        }

        .terms-check a {
            color: #8BC53F;
            text-decoration: none;
        }

        .terms-check a:hover {
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

        .btn-submit:active {
            transform: scale(0.99);
        }

        .register-footer-text {
            text-align: center;
            margin-top: 28px;
            font-size: 0.9rem;
            color: #888888;
        }

        .register-footer-text a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 600;
        }

        .register-footer-text a:hover {
            color: #8BC53F;
        }
    </style>
</head>
<body>

    <!-- =========================
         HEADER / NAVIGATION
         ========================= -->
    <header class="site-header">
        <a href="index.php" class="logo">
            <span class="logo-word">
                <span class="accent">T</span>ension
            </span>
        </a>

        <nav class="main-nav" aria-label="Main navigation">
            <?php foreach ($nav_links as $link): ?>
                <a
                    href="<?= htmlspecialchars($link['href']) ?>"
                    class="nav-link<?= !empty($link['active']) ? ' active' : '' ?>"
                >
                    <?= htmlspecialchars(strtoupper($link['label'])) ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="header-actions">
            <button type="button" aria-label="Change region">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M3 12h18"/>
                    <path d="M12 3c2.5 2.7 4 6 4 9s-1.5 6.3-4 9c-2.5-2.7-4-6-4-9s1.5-6.3 4-9z"/>
                </svg>
            </button>

            <a href="login.php" class="header-icon-link" aria-label="Login" title="Login">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M4 20c1.5-4 5-6 8-6s6.5 2 8 6"/>
                </svg>
            </a>

            <a href="product.php" class="header-icon-link" aria-label="Shopping Cart" title="Shopping Cart">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <path d="M6 8h12l-1 12H7L6 8z"/>
                    <path d="M9 8V6a3 3 0 0 1 6 0v2"/>
                </svg>
            </a>
        </div>
    </header>

    <!-- =========================
         MAIN REGISTER SECTION
         ========================= -->
    <main class="register-hero">
        <div class="register-card">
            <div class="register-header">
                <h1>JOIN <span class="accent">TENSION</span></h1>
                <p>Create an account to track orders and unlock member rewards</p>
            </div>

            <?php if (!empty($error_message)): ?>
                <div class="error-alert" role="alert">
                    <?= htmlspecialchars($error_message) ?>
                </div>
            <?php endif; ?>

            <form action="register.php" method="post" novalidate>
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input
                            type="text"
                            id="first_name"
                            name="first_name"
                            class="form-control"
                            placeholder="John"
                            value="<?= htmlspecialchars($first_name) ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input
                            type="text"
                            id="last_name"
                            name="last_name"
                            class="form-control"
                            placeholder="Doe"
                            value="<?= htmlspecialchars($last_name) ?>"
                            required
                        >
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            class="form-control"
                            placeholder="johndoe"
                            value="<?= htmlspecialchars($username) ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control"
                            placeholder="you@example.com"
                            value="<?= htmlspecialchars($email) ?>"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="phone">Phone / Contact Number</label>
                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        class="form-control"
                        placeholder="09876543210"
                        value="<?= htmlspecialchars($phone) ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="address">Delivery Address</label>
                    <input
                        type="text"
                        id="address"
                        name="address"
                        class="form-control"
                        placeholder="Street, City, Province, Zip Code"
                        value="<?= htmlspecialchars($address) ?>"
                        required
                    >
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            placeholder="••••••••"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input
                            type="password"
                            id="confirm_password"
                            name="confirm_password"
                            class="form-control"
                            placeholder="••••••••"
                            required
                        >
                    </div>
                </div>

                <div class="form-options">
                    <label class="terms-check">
                        <input type="checkbox" name="terms" value="1">
                        <span>I agree to the <a href="#">Terms on Use</a> and <a href="#">Store Policy</a></span>
                    </label>
                </div>

                <button type="submit" class="btn-submit">Create Account</button>
            </form>

            <p class="register-footer-text">
                Already have an account? <a href="login.php">Log In</a>
            </p>
        </div>
    </main>

    <!-- =========================
         FOOTER
         ========================= -->
    <footer class="site-footer">
        <div class="footer-grid">
            <div>
                <h4>Policy</h4>
                <?php foreach ($footer_columns['Policy'] as $item): ?>
                    <a href="#"><?= htmlspecialchars($item) ?></a>
                <?php endforeach; ?>
            </div>
            <div>
                <h4>Our Store</h4>
                <?php foreach ($footer_columns['Our Store'] as $item): ?>
                    <p class="line"><?= htmlspecialchars($item) ?></p>
                <?php endforeach; ?>
            </div>
            <div>
                <h4>Customer Service</h4>
                <?php foreach ($footer_columns['Customer Service'] as $item): ?>
                    <p class="line"><?= htmlspecialchars($item) ?></p>
                <?php endforeach; ?>
                <div class="social-row">
                    <?php foreach ($social_links as $network): ?>
                        <a href="#" aria-label="<?= htmlspecialchars(ucfirst($network)) ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"><circle cx="12" cy="12" r="9"/></svg>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="footer-brand">
                <a href="#" class="logo"></a>
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy; <?= date('Y') ?> — TENSION Energy Drink Company LLC. All Rights Reserved.</span>
            <span>Do Not Sell or Share My Personal Information</span>
        </div>
    </footer>

</body>
</html>