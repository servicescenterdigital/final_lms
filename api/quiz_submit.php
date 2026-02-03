<?php
// api/quiz_submit.php

$user_id = get_logged_in_user_id();
$data = json_decode(file_get_contents('php://input'), true);

$quiz_id = $data['quiz_id'] ?? null;
$answers = $data['answers'] ?? []; // Array of question_id => option_id

if (!$quiz_id) {
    json_response(['error' => 'Quiz ID is required'], 400);
}

$db = get_db();

// Get questions and correct options
$stmt = $db->prepare("SELECT q.id, o.id as correct_option_id FROM questions q JOIN options o ON q.id = o.question_id WHERE q.quiz_id = ? AND o.is_correct = 1");
$stmt->execute([$quiz_id]);
$correct_answers = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

$total_questions = count($correct_answers);
$correct_count = 0;

foreach ($answers as $q_id => $o_id) {
    if (isset($correct_answers[$q_id]) && $correct_answers[$q_id] == $o_id) {
        $correct_count++;
    }
}

$score = $total_questions > 0 ? ($correct_count / $total_questions) * 100 : 0;

$stmt = $db->prepare("SELECT pass_score, course_id, is_final FROM quizzes WHERE id = ?");
$stmt->execute([$quiz_id]);
$quiz = $stmt->fetch();

$passed = $score >= $quiz['pass_score'];

// Record attempt (you might want a quiz_attempts table, but for now we'll just return result)
$response = [
    'score' => $score,
    'passed' => $passed,
    'total' => $total_questions,
    'correct' => $correct_count
];

if ($passed && $quiz['is_final']) {
    // If it's a final exam and they passed, mark course as completed
    $stmt = $db->prepare("UPDATE enrollments SET status = 'completed' WHERE user_id = ? AND course_id = ?");
    $stmt->execute([$user_id, $quiz['course_id']]);
    
    // Generate Certificate
    $cert_id = 'CERT-' . strtoupper(uniqid());
    $stmt = $db->prepare("INSERT INTO certificates (user_id, course_id, certificate_id) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $quiz['course_id'], $cert_id]);
    
    $response['certificate_id'] = $cert_id;
}

json_response($response);
