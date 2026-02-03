<?php
// api/course_progress.php

if (!is_logged_in()) {
    json_response(['error' => 'Unauthorized'], 401);
}

$user_id = get_logged_in_user_id();
$data = json_decode(file_get_contents('php://input'), true);

$lesson_id = $data['lesson_id'] ?? null;
$course_id = $data['course_id'] ?? null;

if (!$lesson_id || !$course_id) {
    json_response(['error' => 'Missing lesson or course ID'], 400);
}

$db = get_db();

// Mark lesson as completed
$stmt = $db->prepare("INSERT IGNORE INTO lessons_completed (user_id, lesson_id) VALUES (?, ?)");
$stmt->execute([$user_id, $lesson_id]);

// Calculate progress
$stmt = $db->prepare("SELECT COUNT(*) FROM lessons WHERE module_id IN (SELECT id FROM modules WHERE course_id = ?)");
$stmt->execute([$course_id]);
$total_lessons = $stmt->fetchColumn();

$stmt = $db->prepare("SELECT COUNT(*) FROM lessons_completed lc JOIN lessons l ON lc.lesson_id = l.id JOIN modules m ON l.module_id = m.id WHERE lc.user_id = ? AND m.course_id = ?");
$stmt->execute([$user_id, $course_id]);
$completed_lessons = $stmt->fetchColumn();

$progress = $total_lessons > 0 ? round(($completed_lessons / $total_lessons) * 100) : 0;

$stmt = $db->prepare("UPDATE enrollments SET progress = ? WHERE user_id = ? AND course_id = ?");
$stmt->execute([$progress, $user_id, $course_id]);

json_response(['success' => true, 'progress' => $progress]);
