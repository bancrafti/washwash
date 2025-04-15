<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Allow access from Vue frontend
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Get raw JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid or missing JSON']);
    exit;
}

// DB Connection
$conn = new mysqli("localhost", "root", "", "washwash");

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'DB Connection Failed']));
}

// Prepare and sanitize
$orderNumber = $conn->real_escape_string($data['orderNumber']);
$orderDate = $conn->real_escape_string($data['orderDate']);
$estimatedDeliveryDate = $conn->real_escape_string($data['estimatedDeliveryDate']);
$deliveryFee = floatval($data['deliveryFee']);
$subtotal = floatval($data['subtotal']);
$grandTotal = floatval($data['grandTotal']);
$cart = $conn->real_escape_string(json_encode($data['cart'])); // Store cart as JSON

// Insert into DB
$sql = "INSERT INTO receipt (order_number, order_date, estimated_delivery_date, delivery_fee, subtotal, grand_total, cart)
        VALUES ('$orderNumber', '$orderDate', '$estimatedDeliveryDate', $deliveryFee, $subtotal, $grandTotal, '$cart')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Order saved successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'DB Error: ' . $conn->error]);
}

$conn->close();
?>
