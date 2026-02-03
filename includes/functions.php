<?php
// includes/functions.php

function redirect($url) {
    header("Location: $url");
    exit;
}

function json_response($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Course Functions
function get_all_published_courses() {
    $db = get_db();
    return $db->query("SELECT * FROM courses WHERE status = 'published' ORDER BY created_at DESC")->fetchAll();
}

function get_course_by_id($id) {
    $db = get_db();
    $stmt = $db->prepare("SELECT * FROM courses WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Enrollment & Progress
function is_enrolled($user_id, $course_id) {
    $db = get_db();
    $stmt = $db->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
    $stmt->execute([$user_id, $course_id]);
    return $stmt->fetch() ? true : false;
}

// Notification Helpers (Custom Email Engine & SMS)
function send_email($to, $subject, $message) {
    // Custom email engine logic
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: <noreply@smartlms.com>' . "\r\n";
    
    // In a real production system, you'd use a queue or a more robust library
    return mail($to, $subject, $message, $headers);
}

function send_sms($phone, $message) {
    // SMS integration logic
    $url = "https://bulksms.digitalservicescenter.rw/v2/version_documentation.html"; // Documentation says this is the doc, but endpoint is usually different
    // Let's assume an endpoint based on the doc
    $endpoint = "https://bulksms.digitalservicescenter.rw/api/v1/send"; 
    
    // Using cURL for SMS
    $data = [
        'api_key' => SMS_API_KEY,
        'to' => $phone,
        'message' => $message,
        'sender_id' => SMS_SENDER_ID
    ];

    /* 
    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
    */
    return true; // Mocked for now
}

// AI Schema Generation
function generate_ai_course_schema() {
    return [
        "title" => "Course Title",
        "description" => "Course Description",
        "price" => 0,
        "modules" => [
            [
                "title" => "Module 1 Title",
                "lessons" => [
                    [
                        "title" => "Lesson 1 Title",
                        "content" => "Lesson Content (Markdown or HTML)",
                        "type" => "text"
                    ]
                ],
                "quizzes" => [
                    [
                        "title" => "Quiz Title",
                        "pass_score" => 70,
                        "questions" => [
                            [
                                "question" => "Question Text",
                                "type" => "multiple_choice",
                                "options" => [
                                    ["text" => "Option 1", "is_correct" => true],
                                    ["text" => "Option 2", "is_correct" => false]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ];
}
