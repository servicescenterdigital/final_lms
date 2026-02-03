<?php
// api/get_quiz.php
$quiz_id = $parts[2] ?? null;
if (!$quiz_id) json_response(['error' => 'No quiz ID'], 400);

$db = get_db();
$stmt = $db->prepare("SELECT * FROM quizzes WHERE id = ?");
$stmt->execute([$quiz_id]);
$quiz = $stmt->fetch();

if (!$quiz) json_response(['error' => 'Quiz not found'], 404);

$stmt = $db->prepare("SELECT id, question_text, type FROM questions WHERE quiz_id = ?");
$stmt->execute([$quiz_id]);
$questions = $stmt->fetchAll();

foreach ($questions as &$q) {
    $stmt = $db->prepare("SELECT id, option_text FROM options WHERE question_id = ?");
    $stmt->execute([$q['id']]);
    $q['options'] = $stmt->fetchAll();
}

$quiz['questions'] = $questions;

json_response($quiz);
