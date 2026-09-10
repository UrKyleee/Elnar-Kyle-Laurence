<?php
require_once 'auth.php';
require_once 'db.php';

if (!function_exists('require_api_login')) {
    function require_api_login() {
        if (empty($_SESSION['user_id'])) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
            exit;
        }
    }
}

require_api_login(); // Halts execution and returns 401 if user is not authenticated

$userId = $_SESSION['user_id'];

// Order processing logic goes here...

header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'message' => 'Order successfully created.',
    'user_id' => $userId
]);