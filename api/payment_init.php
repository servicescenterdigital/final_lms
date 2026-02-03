<?php
// api/payment_init.php

$user_id = get_logged_in_user_id();
$data = json_decode(file_get_contents('php://input'), true);

$course_id = $data['course_id'] ?? null;
$phone = $data['phone'] ?? null;

if (!$course_id || !$phone) {
    json_response(['error' => 'Course ID and phone number are required'], 400);
}

$course = get_course_by_id($course_id);
if (!$course) {
    json_response(['error' => 'Course not found'], 404);
}

if ($course['price'] <= 0) {
    // Free course, auto enroll
    $db = get_db();
    $stmt = $db->prepare("INSERT IGNORE INTO enrollments (user_id, course_id, status) VALUES (?, ?, 'enrolled')");
    $stmt->execute([$user_id, $course_id]);
    json_response(['success' => true, 'message' => 'Enrolled successfully (Free course)']);
}

$request_txn_id = 'TXN' . time() . rand(100, 999);
$amount = $course['price'];

$db = get_db();
$stmt = $db->prepare("INSERT INTO payments (user_id, course_id, amount, request_transaction_id, status) VALUES (?, ?, ?, ?, 'pending')");
$stmt->execute([$user_id, $course_id, $amount, $request_txn_id]);
$payment_id = $db->lastInsertId();

// Call Payment Gateway
$payload = [
    "username" => PAYMENT_USERNAME,
    "password" => PAYMENT_PASSWORD,
    "key" => PAYMENT_KEY,
    "phone" => $phone,
    "amount" => (int)$amount,
    "requesttransactionid" => $request_txn_id,
    "callbackurl" => BASE_URL . "/api/payment-callback"
];

$ch = curl_init(PAYMENT_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = curl_exec($ch);
curl_close($ch);

$res_data = json_decode($response, true);

if ($res_data && isset($res_data['success']) && $res_data['success']) {
    // Save provider txn id if available
    if (isset($res_data['transactionid'])) {
        $stmt = $db->prepare("UPDATE payments SET transaction_id = ? WHERE id = ?");
        $stmt->execute([$res_data['transactionid'], $payment_id]);
    }
    json_response(['success' => true, 'message' => 'Payment initiated', 'txn_id' => $request_txn_id]);
} else {
    json_response(['error' => 'Payment initiation failed', 'details' => $res_data['message'] ?? 'Unknown error'], 500);
}
