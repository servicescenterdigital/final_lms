<?php
// api/payment_callback.php

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['requesttransactionid'])) {
    json_response(['error' => 'Invalid callback data'], 400);
}

$request_txn_id = $data['requesttransactionid'];
$status = $data['status']; // "Completed", "Failed", etc.

$db = get_db();
$stmt = $db->prepare("SELECT * FROM payments WHERE request_transaction_id = ?");
$stmt->execute([$request_txn_id]);
$payment = $stmt->fetch();

if (!$payment) {
    json_response(['error' => 'Transaction not found'], 404);
}

if ($status === 'Completed') {
    $stmt = $db->prepare("UPDATE payments SET status = 'completed', provider_response = ? WHERE id = ?");
    $stmt->execute([json_encode($data), $payment['id']]);

    if ($payment['course_id']) {
        $stmt = $db->prepare("INSERT IGNORE INTO enrollments (user_id, course_id, status, payment_id) VALUES (?, ?, 'enrolled', ?)");
        $stmt->execute([$payment['user_id'], $payment['course_id'], $payment['id']]);
        
        // Notify user via SMS
        $stmt = $db->prepare("SELECT name, email FROM users WHERE id = ?");
        $stmt->execute([$payment['user_id']]);
        $user = $stmt->fetch();
        
        $msg = "Hello " . $user['name'] . ", your payment for the course was successful. You are now enrolled!";
        // send_sms($user['phone'], $msg); // Assuming phone is in users table
    }
} else {
    $stmt = $db->prepare("UPDATE payments SET status = 'failed', provider_response = ? WHERE id = ?");
    $stmt->execute([json_encode($data), $payment['id']]);
}

json_response(['message' => 'success', 'success' => true, 'request_id' => $request_txn_id]);
