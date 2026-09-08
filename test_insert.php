<?php
require_once 'db.php';

try {
    $stmt = $pdo->prepare("
        INSERT INTO users (first_name, last_name, username, email, phone, address, password)
        VALUES (:first_name, :last_name, :username, :email, :phone, :address, :password)
    ");

    $test_email = 'test_' . time() . '@example.com';
    $test_username = 'user_' . time();

    $stmt->execute([
        'first_name' => 'Test',
        'last_name'  => 'User',
        'username'   => $test_username,
        'email'      => $test_email,
        'phone'      => '1234567890',
        'address'    => '123 Test St',
        'password'   => password_hash('password123', PASSWORD_BCRYPT),
    ]);

    echo "<h2 style='color: #8BC53F;'>Record Inserted Successfully!</h2>";
    echo "<p>User ID: " . $pdo->lastInsertId() . "</p>";
} catch (PDOException $e) {
    echo "<h2 style='color: #ff6b6b;'>Insert Failed</h2>";
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}