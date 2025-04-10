<?php
header('Access-Control-Allow-Origin: *');  // Allows requests from any domain
header('Access-Control-Allow-Methods: POST, GET, OPTIONS'); // Allow multiple methods
header('Access-Control-Allow-Headers: Content-Type, Authorization');  // Allow specific headers
header('Content-Type: application/json');

// Handle OPTIONS request (preflight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once('../config/db.php');
$data = json_decode(file_get_contents("php://input"));

// Input validation
if (!isset($data->username) || !isset($data->email) || !isset($data->password)) {
    http_response_code(400);
    echo json_encode(["error" => "Username, email, and password are required"]);
    exit;
}

$username = trim($data->username);
$email = trim($data->email);
$password = password_hash($data->password, PASSWORD_DEFAULT);

// Check if email already exists
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->rowCount() > 0) {
    http_response_code(409);
    echo json_encode(["error" => "Email already registered"]);
} else {
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $email, $password])) {
        http_response_code(201);
        echo json_encode(["success" => "User registered successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Registration failed"]);
    }
}
?>
