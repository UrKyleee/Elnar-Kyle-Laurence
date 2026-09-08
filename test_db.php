<?php
require_once 'db.php';

try {
    // Run a query to fetch the current database name
    $db_name = $pdo->query('SELECT DATABASE()')->fetchColumn();
    
    echo "<h2 style='color: #8BC53F;'>Database Connection Successful!</h2>";
    echo "<p>Connected to database: <strong>" . htmlspecialchars($db_name) . "</strong></p>";
} catch (PDOException $e) {
    echo "<h2 style='color: #ff6b6b;'>Connection Failed</h2>";
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}